<?php

namespace Modules\Online\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseSettingPercent;
use Modules\Quiz\Entities\QuizResult;

class ActivityComplete extends Command
{
    protected $signature = 'online:activity-complete';

    protected $description = 'Auto complete activity. cron chay 1 phút 1 lần (* * * * *)';
    protected $expression = "* * * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $last_check = get_config('activity_complete_log', '2020-01-01 00:00:00');
        $last_check = Carbon::make($last_check)->subMinutes(60)->toDateTimeString();

        $query = OnlineCourseActivityHistory::query()
            ->select(['history.*'])
            ->from('el_online_course_activity_history AS history')
            ->whereNotExists(function (Builder $builder) {
                $builder->select(['id'])
                    ->from('el_online_course_activity_completion AS completion')
                    ->whereColumn('completion.activity_id', '=', 'history.course_activity_id')
                    ->whereColumn('completion.user_id', '=', 'history.user_id')
                    ->whereColumn('completion.user_type', '=', 'history.user_type')
                    ->whereColumn('completion.course_id', '=', 'history.course_id')
                    ->where('completion.status', '=', 1);
            })
            ->whereNotExists(function (Builder $builder){
                $builder->select(['id'])
                    ->from('el_online_result as onl_result')
                    ->whereColumn('onl_result.user_id', '=', 'history.user_id')
                    ->whereColumn('onl_result.user_type', '=', 'history.user_type')
                    ->whereColumn('onl_result.course_id', '=', 'history.course_id')
                    ->where('onl_result.result', '=', 1);
            });

        /*if ($last_check) {
            $query->where('created_at', '>=', $last_check);
        }*/

        $rows = $query->get();
        foreach ($rows as $row) {
            $course_activity = OnlineCourseActivity::find($row->course_activity_id);
            if (!$course_activity) {
                continue;
            }
             if ($course_activity && !$course_activity->checkComplete($row->user_id, $row->user_type)){
                 continue;
             }

            $result = [];
            $result[] = $course_activity->checkComplete($row->user_id, $row->user_type);
            $status = 0;
            foreach ($result as $item){
                if ($item){
                    $status = 1;
                }
                if (isset($item->score) && isset($item->pass_score)) {
                    if ($item->score >= $item->pass_score) {
                        $status = 1;
                    }else{
                        $status = 0;
                    }
                }
            }
            $completion = OnlineCourseActivityCompletion::firstOrNew([
                'user_id' => $row->user_id,
                'user_type' => $row->user_type,
                'activity_id' => $row->course_activity_id,
            ]);
            $completion->course_id = $row->course_id;
            $completion->status = $status;
            $completion->save();

            if ($course_activity->activity_id == 1){
                $activity_scorm = OnlineCourseActivityScorm::find($course_activity->subject_id);
                $score = $activity_scorm->getScoreScorm($row->user_id, $row->user_type);
            }

            if ($course_activity->activity_id == 2){
                $quiz_result = QuizResult::where('quiz_id', '=', $course_activity->subject_id)
                    ->where('user_id', '=', $row->user_id)
                    ->where('type', '=', $row->user_type)
                    ->first();
                if ($quiz_result){
                    $score = isset($quiz_result->reexamine) ? $quiz_result->reexamine : (isset($quiz_result->grade) ? $quiz_result->grade : null);
                }
            }

            DB::table('el_online_course_setting_percent')
            ->updateOrInsert([
                'course_id' => $row->course_id,
                'course_activity_id' => $row->course_activity_id,
            ], [
                'score' => isset($score) ? (int) $score : 0,
            ]);

            echo "Completed activity " . $row->course_activity_id . " - User " . $row->user_id . "\n";
        }

        set_config('activity_complete_log', now()->toDateTimeString());
    }

}
