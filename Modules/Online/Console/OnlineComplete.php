<?php

namespace Modules\Online\Console;

use App\Automail;
use App\CourseResultStatistic;
use App\Models\Categories\Area;
use App\Models\Categories\Position;
use App\Models\Categories\Subject;
use App\Models\CourseComplete;
use App\Profile;
use Arcanedev\LogViewer\Entities\Log;
use Illuminate\Console\Command;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseComplete;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineCourseSettingPercent;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Online\Events\CourseCompleted;
use Modules\Rating\Entities\RatingCourse;
use Modules\ReportNew\Entities\BC15;
use Modules\ReportNew\Entities\ReportNewExportBC05;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\TrainingRoadmap\Entities\TrainingRoadmapFinish;
use Modules\User\Entities\TrainingProcess;
use Modules\User\Entities\UserCompletedSubject;

class OnlineComplete extends Command
{
    protected $signature = 'online:complete';

    protected $description = 'Hoàn thành khóa học online 1 phút 1 lần (* * * * *)';
    protected $expression = "* * * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $query = \DB::query();
        $query->select([
            'register.id',
            'register.course_id',
            'register.user_id',
            'register.user_type',
            'course.subject_id',
            'course.level_subject_id',
            'register.title_id'
        ]);

        $query->from('el_online_register_view AS register')
            ->join('el_online_course AS course', 'course.id', '=', 'register.course_id')
            ->where('course.status', '=', 1)
            ->where('register.status', '=', 1)
            ->where('register.cron_complete','=',0);

        $rows = $query->get();
        foreach ($rows as $row) {
            $result = $this->getResult($row);
            if (empty($result)) {
                continue;
            }

            $model = OnlineResult::firstOrNew(['register_id' => $row->id]);
            $model->register_id = $row->id;
            $model->user_id = $row->user_id;
            $model->user_type = $row->user_type;
            $model->course_id = $row->course_id;
            $model->pass_score = $result->pass_score;
            $model->score = OnlineCourseSettingPercent::getScore($row->course_id, $row->user_id, $row->user_type);
            $model->result = $result->result;
            $model->save();

            $this->updateTrainingProcess($row->user_id, $row->course_id, $result->score, $result->result, $row->user_type);
            $this->updateCompleteCronUser($row->id);

            if ($row->user_type == 1){
                $this->updateReport05($model);
            }

            if ($result->result == 1) {
                $this->updateCompleteCourse($row->user_id,$row->course_id, $row->user_type);
                $this->updateCompletedRoadmapByTitle($row->subject_id, $row->title_id,$row->level_subject_id);
                event(new CourseCompleted($model));
                if ($row->user_type == 1) {
                    $this->updateUserCompletedSubject($row->user_id, $row->subject_id, $row->course_id);
                }
            }

            $this->info('Update completed course ' . $row->course_id . ' user ' . $row->user_id);
        }

        $this->updateResultCourseStatistic();
        $this->info('cron online:complete Success');
    }
    private function updateCompleteCourse($user_id,$course_id, $user_type=1){
        OnlineCourseComplete::updateOrCreate([
            'user_id' => $user_id,
            'user_type' => $user_type,
            'course_id' => $course_id
        ]);
        CourseComplete::updateOrCreate([
            'user_id' => $user_id,
            'user_type' => $user_type,
            'course_id' => $course_id,
            'course_type'=>1
        ]);
    }
    private function updateCompleteCronUser($register_id){
        $offlineResult = OnlineRegister::whereId($register_id)->first();
        $offlineResult->cron_complete = 1;
        $offlineResult->save();
    }
    private function updateCompletedRoadmapByTitle($subject_id, $title_id,$level_subject_id){
        $exists = TrainingRoadmap::where(['subject_id'=>$subject_id,'title_id'=>$title_id])->exists();
        if ($exists) {
            $userFinish=(int)TrainingRoadmapFinish::where(['title_id' => $title_id, 'level_subject_id' => $level_subject_id])->value('user_finish');
            TrainingRoadmapFinish::updateOrCreate(
                ['title_id' => $title_id, 'level_subject_id' => $level_subject_id],
                ['level_subject_id' => $level_subject_id, 'user_finish' => $userFinish+1]
            );
        }
    }
    private function updateUserCompletedSubject($user_id,$subject_id,$course_id){
        UserCompletedSubject::updateOrCreate(['user_id'=>$user_id,'subject_id'=>$subject_id],[
            'course_id'=>$course_id,
            'course_type'=>1,
            'date_completed'=>date('Y-m-d H:i:s'),
            'process_type'=>'E'
        ]);
        // update report bc15
        $this->updateReportBC15($user_id,$subject_id);
    }
    private function updateReportBC15($user_id,$subject_id){
        $subject_code = Subject::find($subject_id)->code;
        $subjects = BC15::where(['user_id'=>$user_id])->select('subject')->first();
        if ($subjects){
            $subjects = json_decode($subjects['subject'],true);
            foreach ($subjects as $index => $subject) {
                if ($subject['code']==$subject_code)
                    $subjects[$index]['type']='O';
            }
            $subjects = collect($subjects)->toJson();
            BC15::where(['user_id'=>$user_id])->update(['subject'=>$subjects]);
        }
    }
    private function updateSendEmailUserCompleted($course_id){
        $users = OnlineCourseComplete::getUserCompleted($course_id);
        $course = OnlineCourse::find($course_id);
        foreach ($users as $user) {
            $progress = OnlineCourse::percentCompleteCourseByUser($course_id, $user->user_id);
            $signature = getMailSignature($user->user_id);

            $automail = new Automail();
            $automail->template_code = 'course_completed';
            $automail->params = [
                'signature' => $signature,
                'gender' => $user->gender=='1'?'Anh':'Chị',
                'full_name' => $user->full_name,
                'course_code' => $course->code,
                'course_name' => $course->name,
                'course_type' => 'Trực tuyến',
                'start_date' => get_date($course->start_date),
                'end_date' => get_date($course->end_date),
                'completion' => 'Hoàn thành',
                'url' => route('module.online.detail', ['id' => $course->id]),
                'progress' => $progress,
            ];
            $automail->users = [$user->user_id];
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course_id;
            $automail->object_type = 'online_completed';
            $automail->addToAutomail();
        }
    }
    private function updateResultCourseStatistic(){
        CourseResultStatistic::update_count_result_statistic(1,1); // khóa học online kết quả đậu
        CourseResultStatistic::update_count_result_statistic(1,0); // khóa học online kết quả rớt
        CourseResultStatistic::update_count_result_statistic(2,1); // khóa học offline kết quả đậu
        CourseResultStatistic::update_count_result_statistic(2,0); // khóa học offline kết quả rớt
    }
    private function updateTrainingProcess($user_id,$course_id,$score,$pass, $user_type){

        TrainingProcess::where([
            'user_id'=>$user_id,
            'course_id'=>$course_id,
            'course_type'=>1,
            'user_type' => $user_type
        ])->update([
            'pass'=>$pass,
            'mark'=>$score,
            'time_complete'=>date('Y-m-d H:i:s')
        ]);
    }
    private function getResult($row)
    {
        $object = new \stdClass();
        $object->result = 0;
        $object->score = null;
        $object->pass_score = 0;

        /* check result */
        $condition = OnlineCourseCondition::where('course_id', '=', $row->course_id)->first();
        if (empty($condition)) {
            return false;
        }

        $activity_condition = explode(',', $condition->activity);
        $count_condition = count($activity_condition);
        $count_complete = 0;

        $query = OnlineCourseActivity::where('course_id', '=', $row->course_id);
        $query->whereIn('id', $activity_condition);
        $activities = $query->get();

        /* Activities completed */
        $result = [];
        foreach ($activities as $activity) {
            $completed = $activity->checkComplete($row->user_id, $row->user_type);
            $result[] = $completed;

            if (in_array($activity->id, $activity_condition) && $completed) {
                $count_complete += 1;
            }
        }

        /* Get score */
        $score = [];
        $count_score = 0;
        foreach ($result as $item) {
            if (isset($item->score)) {
                $score[] = $item->score;
            }
            if (isset($item->pass_score)) {
                $object->pass_score = $item->pass_score;
            }
            if (isset($item->score) && isset($item->pass_score)) {
                if ($item->score >= $item->pass_score) {
                    $count_score += 1;
                }
            }
        }

        if ($condition->grade_methor) {
            if ($condition->grade_methor == 1) {
                $object->score = (count($score) > 0 ? max($score) : null);
            }
            if ($condition->grade_methor == 2) {
                if (count($score) > 0){
                    $total = 0;
                    foreach ($score as $item) {
                        $total += $item;
                    }
                    $object->score = $total / (count($score) > 0 ? count($score) : 1);
                }
            }
            if ($condition->grade_methor == 3) {
                if (count($score) > 0) {
                    foreach ($score as $item) {
                        $object->score = $item;
                    }
                }
            }
        } else {
            if (count($score) > 0){
                $total = 0;
                foreach ($score as $item) {
                    $total += $item;
                }
                $object->score = $total / (count($score) > 0 ? count($score) : 1);
            }
        }

        if ($condition->rating) {
            $count_condition += 1;
            $check = RatingCourse::where('course_id', '=', $row->course_id)
                ->where('user_id', '=', $row->user_id)
                ->where('user_type', '=', $row->user_type)
                ->where('send', '=', 1)
                ->exists();
            if ($check) {
                $count_complete += 1;
            }
        }

        if ($count_condition == $count_complete) {
            $object->result = count($score) > 0 ? (count($score) == $count_score ? 1 : 0) : 1;
        }

        return $object;
    }

    private function updateReport05($model){
        $profile = Profile::find($model->user_id);
        $position = Position::find($profile->position_id);
        $title = @$profile->titles;
        $unit_1 = @$profile->unit;
        $unit_2 = @$unit_1->parent;
        $unit_3 = @$unit_2->parent;

        $area = Area::find(@$unit_1->area_id);

        $course = OnlineCourse::find($model->course_id);
        $subject = Subject::find($course->subject_id);
        $course_time = preg_replace("/[^0-9]/", '', $course->course_time);

        ReportNewExportBC05::query()->updateOrCreate([
            'user_id' => $profile->user_id,
            'course_id' => $course->id,
            'course_type' => 1,
        ],[
            'course_id' => $course->id,
            'course_code' => $course->code,
            'course_name' => $course->name,
            'course_type' => 1,
            'subject_id' => @$subject->id,
            'subject_name' => @$subject->name,
            'training_unit' => $course->training_unit,
            'training_type_id' => null,
            'training_type_name' => null,
            'training_form_id' => null,
            'training_form_name' => null,
            'training_area_id' => null,
            'training_area_name' => null,
            'course_time' => $course_time,
            'attendance' => null,
            'start_date' => $course->start_date,
            'end_date' => $course->end_date,
            'score' => @$model->score,
            'result' => $model->result,
            'user_id' => $profile->user_id,
            'user_code' => $profile->code,
            'fullname' => $profile->full_name,
            'email' => $profile->email,
            'phone' => $profile->phone,
            'area_id' => @$area->id,
            'area_code' => @$area->code,
            'area_name' => @$area->name,
            'unit_id_1' => @$unit_1->id,
            'unit_code_1' => @$unit_1->code,
            'unit_name_1' => @$unit_1->name,
            'unit_id_2' => @$unit_2->id,
            'unit_code_2' => @$unit_2->code,
            'unit_name_2' => @$unit_2->name,
            'unit_id_3' => @$unit_3->id,
            'unit_code_3' => @$unit_3->code,
            'unit_name_3' => @$unit_3->name,
            'position_name' => @$position->name,
            'title_id' => @$title->id,
            'title_code' => @$title->code,
            'title_name' => @$title->name,
            'status_user' => $profile->status,
            'note' => '',
        ]);
    }
}
