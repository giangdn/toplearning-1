<?php

namespace App\Observers;

use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineRegister;

class OnlineCourseActivityCompletionObserver extends BaseObserver
{
    /**
     * Handle the online course activity completion "created" event.
     *
     * @param  \App\OnlineCourseActivityCompletion  $onlineCourseActivityCompletion
     * @return void
     */
    public function created(OnlineCourseActivityCompletion $onlineCourseActivityCompletion)
    {
        $this->updateCronCompleteCourseStatus($onlineCourseActivityCompletion);

        $courseName = OnlineCourse::find($onlineCourseActivityCompletion->course_id)->name;
        $action = 'Thêm hoàn thành hoạt động (khóa học online)';
        parent::saveHistory($onlineCourseActivityCompletion,'Insert',$action,$courseName, $onlineCourseActivityCompletion->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course activity completion "updated" event.
     *
     * @param  \App\OnlineCourseActivityCompletion  $onlineCourseActivityCompletion
     * @return void
     */
    public function updated(OnlineCourseActivityCompletion $onlineCourseActivityCompletion)
    {
        $this->updateCronCompleteCourseStatus($onlineCourseActivityCompletion);

        $courseName = OnlineCourse::find($onlineCourseActivityCompletion->course_id)->name;
        $action = 'Cập nhật hoàn thành hoạt động (khóa học online)';
        parent::saveHistory($onlineCourseActivityCompletion,'Insert',$action,$courseName, $onlineCourseActivityCompletion->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course activity completion "deleted" event.
     *
     * @param  \App\OnlineCourseActivityCompletion  $onlineCourseActivityCompletion
     * @return void
     */
    public function deleted(OnlineCourseActivityCompletion $onlineCourseActivityCompletion)
    {
        //
    }

    /**
     * Handle the online course activity completion "restored" event.
     *
     * @param  \App\OnlineCourseActivityCompletion  $onlineCourseActivityCompletion
     * @return void
     */
    public function restored(OnlineCourseActivityCompletion $onlineCourseActivityCompletion)
    {
        //
    }

    /**
     * Handle the online course activity completion "force deleted" event.
     *
     * @param  \App\OnlineCourseActivityCompletion  $onlineCourseActivityCompletion
     * @return void
     */
    public function forceDeleted(OnlineCourseActivityCompletion $onlineCourseActivityCompletion)
    {
        //
    }
    private function updateCronCompleteCourseStatus(OnlineCourseActivityCompletion $onlineCourseActivityCompletion){
        $onlineRegister = OnlineRegister::where(['user_id'=>$onlineCourseActivityCompletion->user_id,'course_id'=>$onlineCourseActivityCompletion->course_id])->first();
        $onlineRegister->cron_complete = 0;
        $onlineRegister->save();
    }
}
