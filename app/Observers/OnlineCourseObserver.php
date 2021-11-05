<?php

namespace App\Observers;

use App\CourseView;
use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingObject;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\Unit;
use \Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseView;
use Modules\PlanApp\Entities\PlanAppTemplate;
use Modules\Rating\Entities\RatingTemplate;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\User\Entities\TrainingProcess;

class OnlineCourseObserver extends BaseObserver
{
    /**
     * Handle the online course "created" event.
     *
     * @param  \App\OnlineCourse  $onlineCourse
     * @return void
     */
    public function created(OnlineCourse $onlineCourse)
    {
        $this->syncCourseView($onlineCourse);
        parent::saveHistory($onlineCourse,'Insert','Thêm khóa học online');
    }

    /**
     * Handle the online course "updated" event.
     *
     * @param  \App\OnlineCourse  $onlineCourse
     * @return void
     */
    public function updated(OnlineCourse $onlineCourse)
    {
        $this->syncCourseView($onlineCourse);
        if ($onlineCourse->isDirty('approved_step'))
            $action = 'Phê duyệt khóa học online';
        elseif($onlineCourse->isDirty('lock_course'))
            $action = 'Khóa/mở khóa học online';
        elseif($onlineCourse->isDirty('isopen'))
            $action = 'Bật/tắt khóa học online';
        else
            $action = 'Cập nhật khóa học online';
        parent::saveHistory($onlineCourse,'Update',$action);
    }

    /**
     * Handle the online course "deleted" event.
     *
     * @param  \App\OnlineCourse  $onlineCourse
     * @return void
     */
    public function deleted(OnlineCourse $onlineCourse)
    {
        parent::saveHistory($onlineCourse,'Delete','Xóa khóa học online');
        OnlineCourseView::destroy($onlineCourse->id);
        CourseView::where(['course_id'=>$onlineCourse->id,'course_type'=>1])->delete();
    }

    /**
     * Handle the online course "restored" event.
     *
     * @param  \App\OnlineCourse  $onlineCourse
     * @return void
     */
    public function restored(OnlineCourse $onlineCourse)
    {
        //
    }

    /**
     * Handle the online course "force deleted" event.
     *
     * @param  \App\OnlineCourse  $onlineCourse
     * @return void
     */
    public function forceDeleted(OnlineCourse $onlineCourse)
    {
        //
    }
    private function syncCourseView(OnlineCourse $onlineCourse){
        $model = OnlineCourseView::firstOrNew(['id'=>$onlineCourse->id]);
        $model->fill($onlineCourse->toArray());
//        $model->unit_name = $onlineCourse->unit_id>0?Unit::findOrFail($onlineCourse->unit_id)->name:null;

        $trainingProgram = TrainingProgram::findOrFail($onlineCourse->training_program_id);
        $model->training_program_code = $onlineCourse->training_program_id>0?$trainingProgram->code:null;
        $model->training_program_name = $onlineCourse->training_program_id>0?$trainingProgram->name:null;

        $subject = Subject::findOrFail($onlineCourse->subject_id);
        $model->subject_code = $onlineCourse->subject_id>0?$subject->code:null;
        $model->subject_name = $onlineCourse->subject_id>0?$subject->name:null;

        $model->template_name = $onlineCourse->template_id>0?RatingTemplate::findOrFail($onlineCourse->template_id)->name:null;
        $model->plan_app_template_name = $onlineCourse->plan_app_template>0 ? PlanAppTemplate::findOrFail($onlineCourse->plan_app_template)->name:null;
        $model->title_join_name = $onlineCourse->title_join_id>0?Titles::findOrFail($onlineCourse->title_join_id)->name:null;
        $model->title_recommend_name = $onlineCourse->title_recommend_id>0?Titles::findOrFail($onlineCourse->title_recommend_id)->name:null;
        $model->training_object_name = $onlineCourse->training_object_id>0?TrainingObject::findOrFail($onlineCourse->training_object_id)->name:null;
        $model->training_object_id = !empty($onlineCourse->training_object_id) ? $onlineCourse->training_object_id : null;
        $model->is_roadmap = TrainingRoadmap::where(['subject_id'=> $onlineCourse->subject_id])->exists()?1:0;
        $model->save();

        $courseView =  CourseView::firstOrNew(['course_id'=>$onlineCourse->id,'course_type'=>1]);
        $courseView->fill($model->toArray());
        $courseView->course_id=$onlineCourse->id;
        $courseView->training_object_id = !empty($onlineCourse->training_object_id) ? $onlineCourse->training_object_id : null;
        $courseView->course_type=1;
        $courseView->save();

        if ($onlineCourse->isDirty(['course_code','course_name','subject_id','start_date','end_date']))
            TrainingProcess::where(['course_id'=>$onlineCourse->id,'course_type'=>1])->update([
                'course_code'=>$onlineCourse->code,
                'course_name'=>$onlineCourse->name,
                'subject_code'=>$subject->code,
                'subject_name'=>$subject->name,
                'start_date'=>$onlineCourse->start_date,
                'end_date'=>$onlineCourse->end_date,
            ]);
    }
}
