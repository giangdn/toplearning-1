<?php

namespace App\Observers;

use App\CourseRegisterView;
use App\ProfileView;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineRegisterView;
use Modules\Quiz\Entities\QuizUserSecondary;

class OnlineRegisterObserver extends BaseObserver
{
    /**
     * Handle the online register "created" event.
     *
     * @param  \App\OnlineRegister  $onlineRegister
     * @return void
     */
    public function created(OnlineRegister $onlineRegister)
    {
        $this->syncRegisterView($onlineRegister);
        $courseName = OnlineCourse::find($onlineRegister->course_id)->name;
        $student = ProfileView::find($onlineRegister->user_id)->full_name;
        $action = 'Thêm ghi danh '.$student.' (khóa học online)';
        parent::saveHistory($onlineRegister,'Insert',$action,$courseName, $onlineRegister->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online register "updated" event.
     *
     * @param  \App\OnlineRegister  $onlineRegister
     * @return void
     */
    public function updated(OnlineRegister $onlineRegister)
    {
        $this->syncRegisterView($onlineRegister);
        $courseName = OnlineCourse::find($onlineRegister->course_id)->name;
        $student = ProfileView::find($onlineRegister->user_id)->full_name;
        $action = 'Cập nhật ghi danh '.$student.' (khóa học online)';
        if ($onlineRegister->isDirty('approved_step')){
            $action = 'Phê duyệt ghi danh '.$student.' (khóa học online)';
        }
        parent::saveHistory($onlineRegister,'Update',$action,$courseName, $onlineRegister->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online register "deleted" event.
     *
     * @param  \App\OnlineRegister  $onlineRegister
     * @return void
     */
    public function deleted(OnlineRegister $onlineRegister)
    {
        OnlineRegisterView::destroy($onlineRegister->id);
        $courseName = OnlineCourse::find($onlineRegister->course_id)->name;
        $student = ProfileView::find($onlineRegister->user_id)->full_name;
        $action = 'Xóa ghi danh '.$student.' (khóa học online)';
        parent::saveHistory($onlineRegister,'Delete',$action,$courseName, $onlineRegister->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online register "restored" event.
     *
     * @param  \App\OnlineRegister  $onlineRegister
     * @return void
     */
    public function restored(OnlineRegister $onlineRegister)
    {
        //
    }

    /**
     * Handle the online register "force deleted" event.
     *
     * @param  \App\OnlineRegister  $onlineRegister
     * @return void
     */
    public function forceDeleted(OnlineRegister $onlineRegister)
    {
        //
    }
    private function syncRegisterView(OnlineRegister $onlineRegister){
        $model = OnlineRegisterView::firstOrNew(['id'=>$onlineRegister->id]);
        $onRegister = OnlineRegister::find($onlineRegister->id)->toArray();
        $model->fill($onRegister);

        if ($onRegister['user_type'] == 1){
            $profile = ProfileView::where(['user_id'=>$onlineRegister->user_id])->first();
            $model->code=$profile->code;
            $model->full_name=$profile->full_name;
            $model->email=$profile->email;

            $model->title_id=$profile->title_id;
            $model->title_code=$profile->title_code;
            $model->title_name=$profile->title_name;

            $model->position_id=$profile->position_id;
            $model->position_code=$profile->position_code;
            $model->position_name=$profile->position_name;

            $model->unit_id=$profile->unit_id;
            $model->unit_code=$profile->unit_code;
            $model->unit_name=$profile->unit_name;

            $model->parent_unit_id=$profile->parent_unit_id;
            $model->parent_unit_code=$profile->parent_unit_code;
            $model->parent_unit_name=$profile->parent_unit_name;

            $model->area_id=$profile->area_id;
            $model->area_code=$profile->area_code;
            $model->area_name=$profile->area_name;
        }else{
            $profile = QuizUserSecondary::find($onlineRegister->user_id);

            $model->code=$profile->code;
            $model->full_name=$profile->name;
            $model->email=$profile->email;
        }

        if ($onlineRegister->status>=0){
            $model->approved_by_2 = $model->updated_by;
            $model->approved_date_2 = $model->updated_at;
            $model->status_level_2 = $model->status;
        }
        $model->user_type = $onRegister['user_type'];
        $model->save();

        $courseRegisterView = CourseRegisterView::firstOrNew([
            'user_id'=>$onlineRegister->user_id,
            'user_type'=>$onRegister['user_type'],
            'course_id'=>$onlineRegister->course_id,
            'course_type'=>1
        ]);
        $courseRegisterView->fill($model->toArray());
        $courseRegisterView->register_id=$model->id;
        $courseRegisterView->course_type=1;
        $courseRegisterView->save();
    }
}
