<?php

namespace App\Observers;

use App\CourseView;
use App\Models\Categories\Area;
use App\Models\Categories\Subject;
use App\Models\Categories\TeacherType;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingObject;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use \Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseView;
use Modules\PlanApp\Entities\PlanAppTemplate;
use Modules\Quiz\Entities\Quiz;
use Modules\Rating\Entities\RatingTemplate;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\User\Entities\TrainingProcess;

class OfflineCourseObserver extends BaseObserver
{
    /**
     * Handle the offline course "created" event.
     *
     * @param  \App\OfflineCourse  $offlineCourse
     * @return void
     */
    public function created(OfflineCourse $offlineCourse)
    {
        $this->syncCourseView($offlineCourse);
        parent::saveHistory($offlineCourse,'Insert','Thêm khóa học tập trung');
    }

    /**
     * Handle the offline course "updated" event.
     *
     * @param  \App\OfflineCourse  $offlineCourse
     * @return void
     */
    public function updated(OfflineCourse $offlineCourse)
    {
        $this->syncCourseView($offlineCourse);
        if ($offlineCourse->isDirty('approved_step'))
            $action = 'Phê duyệt khóa học tập trung';
        elseif($offlineCourse->isDirty('lock_course'))
            $action = 'Khóa/mở khóa học tập trung';
        elseif($offlineCourse->isDirty('isopen'))
            $action = 'Bật/tắt khóa học tập trung';
        else
            $action = 'Cập nhật khóa học tập trung';
        parent::saveHistory($offlineCourse,'Insert',$action);
    }

    /**
     * Handle the offline course "deleted" event.
     *
     * @param  \App\OfflineCourse  $offlineCourse
     * @return void
     */
    public function deleted(OfflineCourse $offlineCourse)
    {
        parent::saveHistory($offlineCourse,'Delete','Xóa khóa học tập trung');
        OfflineCourseView::destroy($offlineCourse->id);
        CourseView::where(['course_id'=>$offlineCourse->id,'course_type'=>2])->delete();
    }

    /**
     * Handle the offline course "restored" event.
     *
     * @param  \App\OfflineCourse  $offlineCourse
     * @return void
     */
    public function restored(OfflineCourse $offlineCourse)
    {
        //
    }

    /**
     * Handle the offline course "force deleted" event.
     *
     * @param  \App\OfflineCourse  $offlineCourse
     * @return void
     */
    public function forceDeleted(OfflineCourse $offlineCourse)
    {
        //
    }
    private function syncCourseView(OfflineCourse $offlineCourse){
        $model = OfflineCourseView::firstOrNew(['id'=>$offlineCourse->id]);
        $model->fill($offlineCourse->toArray());
//        $model->unit_name = $offlineCourse->unit_id>0?Unit::findOrFail($offlineCourse->unit_id)->name:null;

        $trainingProgram = TrainingProgram::findOrFail($offlineCourse->training_program_id);
        $model->training_program_code = $offlineCourse->training_program_id>0?$trainingProgram->code:null;
        $model->training_program_name = $offlineCourse->training_program_id>0?$trainingProgram->name:null;

        $subject = Subject::findOrFail($offlineCourse->subject_id);
        $model->subject_code = $offlineCourse->subject_id>0?$subject->code:null;
        $model->subject_name = $offlineCourse->subject_id>0?$subject->name:null;

        $model->training_form_name = $offlineCourse->training_form_id>0? TrainingForm::findOrFail($offlineCourse->training_form_id)->name:null;
        $model->training_location_name = $offlineCourse->training_location_id>0?TrainingLocation::findOrFail($offlineCourse->training_location_id)->name:null;
        $model->training_area_name = $offlineCourse->training_area_id>0?Area::findOrFail($offlineCourse->training_area_id)->name:null;

        $model->training_partner_id = $offlineCourse->training_partner_id ? $offlineCourse->training_partner_id : null;
        $model->training_unit = $offlineCourse->training_unit ? $offlineCourse->training_unit : null;

        $model->training_partner_type = $offlineCourse->training_partner_type;
        $model->training_unit_type = $offlineCourse->training_unit_type;

        $model->template_name = $offlineCourse->template_id>0?RatingTemplate::findOrFail($offlineCourse->template_id)->name:null;
        $model->plan_app_template_name = $offlineCourse->plan_app_template>0? PlanAppTemplate::findOrFail($offlineCourse->plan_app_template)->name:null;
        $model->quiz_name = $offlineCourse->quiz_id>0?Quiz::findOrFail($offlineCourse->quiz_id)->name:null;
        if ($offlineCourse->course_employee==1)
            $model->course_employee_name = 'CBNV tân tuyển';
        elseif ($offlineCourse->course_employee==2)
            $model->course_employee_name = 'CBNV hiện hữu';
        if ($offlineCourse->course_action==1)
            $model->course_action_name = 'Kế hoạch';
        elseif ($offlineCourse->course_action==2)
            $model->course_action_name = 'Phát sinh';
        $model->title_join_name = $offlineCourse->title_join_id>0?Titles::findOrFail($offlineCourse->title_join_id)->name:null;
        $model->title_recommend_name = $offlineCourse->title_recommend_id>0?Titles::findOrFail($offlineCourse->title_recommend_id)->name:null;
        $model->training_object_id = !empty($offlineCourse->training_object_id) ? $offlineCourse->training_object_id : null;
        $model->teacher_type_name = $offlineCourse->teacher_type_id>0?TeacherType::findOrFail($offlineCourse->teacher_type_id)->name:null;
        $model->training_type_name = $offlineCourse->training_type_id>0?TrainingType::findOrFail($offlineCourse->training_type_id)->name:null;
        $model->is_roadmap = TrainingRoadmap::where(['subject_id'=> $offlineCourse->subject_id])->exists()?1:0;
        $model->save();

//        $offlineCourseView=OfflineCourseView::where($offlineCourse->id)->first()->toArray();dd($offlineCourseView);
        $courseView =  CourseView::firstOrNew(['course_id'=>$offlineCourse->id,'course_type'=>2]);
        $courseView->fill($model->toArray());
        $courseView->course_id=$offlineCourse->id;
        $courseView->training_object_id = !empty($offlineCourse->training_object_id) ? $offlineCourse->training_object_id : null;
        $courseView->course_type=2;
        $courseView->save();
        // update training_process
        if ($offlineCourse->isDirty(['course_code','course_name','subject_id','start_date','end_date']))
            TrainingProcess::where(['course_id'=>$offlineCourse->id,'course_type'=>2])->update([
                'course_code'=>$offlineCourse->code,
                'course_name'=>$offlineCourse->name,
                'subject_code'=>$subject->code,
                'subject_name'=>$subject->name,
                'start_date'=>$offlineCourse->start_date,
                'end_date'=>$offlineCourse->end_date,
            ]);
    }
}
