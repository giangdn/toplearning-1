<?php

namespace Modules\Quiz\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizResult;
use App\EmulationPromotion;

class QuizComplete extends Command
{
    protected $signature = 'quiz:complete';

    protected $description = 'Quiz complete.';

    protected $expression = "* * * * *";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now_2h = Carbon::now()->subHours(2)->format('Y-m-d H:i:s');

        $query = QuizAttempts::query()
            ->where('state', '=', 'completed')
            ->where('updated_at', '>', $now_2h);

        $rows = $query->get();

        foreach ($rows as $row) {
            $this->info('Grade attempt: ' . $row->id);
            $quiz = $row->quiz;

            if (empty($quiz)) {
                continue;
            }

            $grade = 0;
            if ($quiz->grade_methor == 1) {
                $sumgrade = QuizAttempts::where('quiz_id', '=', $row->quiz_id)
                    ->where('user_id', '=', $row->user_id)
                    ->where('type', '=', $row->type)
                    ->select(\DB::raw('MAX(sumgrades) AS total_grade'))
                    ->first();
                if ($sumgrade) {
                    $grade = $sumgrade->total_grade;
                }
            }

            if ($quiz->grade_methor == 2) {
                $sumgrade = QuizAttempts::where('quiz_id', '=', $row->quiz_id)
                    ->where('user_id', '=', $row->user_id)
                    ->where('type', '=', $row->type)
                    ->select(\DB::raw('AVG(sumgrades) AS total_grade'))
                    ->first();
                if ($sumgrade) {
                    $grade = $sumgrade->total_grade;
                }
            }

            if ($quiz->grade_methor == 3) {
                $sumgrade = QuizAttempts::where('quiz_id', '=', $row->quiz_id)
                    ->where('user_id', '=', $row->user_id)
                    ->where('type', '=', $row->type)
                    ->where('attempt', '=', 1)
                    ->first();
                if ($sumgrade) {
                    $grade = $sumgrade->sumgrades;
                }
            }

            if ($quiz->grade_methor == 4) {
                $sumgrade = QuizAttempts::where('quiz_id', '=', $row->quiz_id)
                    ->where('user_id', '=', $row->user_id)
                    ->where('type', '=', $row->type)
                    ->where('attempt', '=', function ($subquery) use ($row) {
                        $subquery->select(\DB::raw('MAX(attempt) AS max_attempt'))
                            ->from('el_quiz_attempts')
                            ->where('quiz_id', '=', $row->quiz_id)
                            ->where('user_id', '=', $row->user_id)
                            ->first();
                    })
                    ->first();
                if ($sumgrade) {
                    $grade = $sumgrade->sumgrades;
                }
            }

            $check_promnotion = 0;
            $result = QuizResult::where('quiz_id', '=', $row->quiz_id)
                ->where('user_id', '=', $row->user_id)
                ->where('type', '=', $row->type)
                ->first();
            if ($result && $result->grade != $grade){
                $check_promnotion = 1;
            }

            /*$result = QuizResult::query()
                ->where('quiz_id', '=', $row->quiz_id)
                ->where('user_id', '=', $row->user_id)
                ->where('type', '=', $row->type)
                ->updateOrCreate([
                    'grade' => $grade,
                    'result' => ($grade >= $quiz->pass_score) ? 1 : 0
                ]);*/

            if ($result) {
                $result->grade = $grade;
                $result->result = ($grade >= $quiz->pass_score) ? 1 : 0;
                $result->save();
            } else {
                $result = new QuizResult();
                $result->quiz_id = $row->quiz_id;
                $result->user_id = $row->user_id;
                $result->type = $row->type;
                $result->grade = $grade;
                $result->result = ($grade >= $quiz->pass_score) ? 1 : 0;
                $result->save();
            }

            $setting = PromotionCourseSetting::where('course_id', $result->quiz_id)
                ->where('type', 3)
                ->where('status',1)
                ->whereIn('code', ['complete', 'landmarks'])
                ->get();

            if ($result->result == 1 && $setting->count() > 0 && $result->type == 1) {
                foreach ($setting as $item){
                    $user_point = PromotionUserPoint::firstOrCreate([
                        'user_id' => $result->user_id,
                    ], [
                        'point' => 0,
                        'level_id' => 0
                    ]);
                    if ($item->method == 0 && $item->point){
                        if ($item->start_date && $item->end_date){
                            if (get_date($item->start_date, 'Y-m-d 00:00:00') <= get_date($result->updated_at, 'Y-m-d H:i:s') && get_date($result->updated_at, 'Y-m-d H:i:s') <= get_date($item->end_date, 'Y-m-d 23:59:59')){
                                $user_point->point += $item->point;
                            }
                        }else{
                            $user_point->point += $item->point;
                        }

                        $user_point->level_id = PromotionLevel::levelUp($user_point->point, $result->user_id);

                        if ($check_promnotion == 1) {
                            $user_point->update();

                            $this->saveHistoryPromotion($result->user_id, $item->point, $item->course_id, $item->id);
                        }
                    }
                    if ($item->method == 1 && $item->point){
                        if ($item->min_score <= $result->grade && $result->grade <= $item->max_score){
                            $user_point->point += $item->point;
                            $user_point->level_id = PromotionLevel::levelUp($user_point->point, $result->user_id);
                            if ($check_promnotion == 1){
                                $user_point->update();

                                $this->saveHistoryPromotion($result->user_id, $item->point, $item->course_id, $item->id);
                                $this->savePromotionEmulation($result->user_id, $item->point, $item->course_id, 3);
                            }
                        }
                    }
                }
            }

            $this->info('Quiz result: ' . $row->quiz_id . ' - Grade: ' . $grade);
        }
    }

    private function levelUp($point)
    {
        $level = PromotionLevel::query()->where('point','<=', $point);

        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }

    private function saveHistoryPromotion($user_id, $point, $course_id, $promotion_course_setting_id){
        $history = new PromotionUserHistory();
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = 3;
        $history->course_id = $course_id;
        $history->promotion_course_setting_id = $promotion_course_setting_id;
        $history->save();

        $quiz_name = Quiz::query()->find($course_id)->name;

        $model = new Notify();
        $model->user_id = $user_id;
        $model->subject = 'Thông báo đạt điểm thưởng kỳ thi.';
        $model->content = 'Bạn đã đạt điểm thưởng là "'. $point .'" điểm của kỳ thi "'. $quiz_name .'"';
        $model->url = null;
        $model->created_by = 0;
        $model->save();

        $content = \Str::words(html_entity_decode(strip_tags($model->content)), 10);
        $redirect_url = route('module.notify.view', [
            'id' => $model->id,
            'type' => 1
        ]);

        $notification = new AppNotification();
        $notification->setTitle($model->subject);
        $notification->setMessage($content);
        $notification->setUrl($redirect_url);
        $notification->add($user_id);
        $notification->save();
    }

    private function savePromotionEmulation($user_id,$point,$course_id, $type){
        $history = EmulationPromotion::firstOrNew(['course_id' => $course_id, 'user_id' => $user_id, 'type' => $type]);
        $history->user_id = $user_id;
        $history->point = $point;
        $history->type = 3;
        $history->course_id = $course_id;
        $history->save();
    }

}
