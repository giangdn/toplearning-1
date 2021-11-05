<?php

namespace App\Observers;

use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineRegisterView;

class OnlineConditionObserver extends BaseObserver
{
    /**
     * Handle the online condition "created" event.
     *
     * @param  \App\OnlineCondition  $onlineCondition
     * @return void
     */
    public function created(OnlineCourseCondition $onlineCondition)
    {
        $this->updateCronCompleteCourseStatus($onlineCondition);
        $courseName = OnlineCourse::find($onlineCondition->course_id)->name;
        $action = 'Thêm điều kiện hoàn thành (khóa học online)';
        parent::saveHistory($onlineCondition,'Insert',$action,$courseName, $onlineCondition->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online condition "updated" event.
     *
     * @param  \App\OnlineCondition  $onlineCondition
     * @return void
     */
    public function updated(OnlineCourseCondition $onlineCondition)
    {
        $this->updateCronCompleteCourseStatus($onlineCondition);
        $courseName = OnlineCourse::find($onlineCondition->course_id)->name;
        $action = 'Cập nhật điều kiện hoàn thành (khóa học online)';
        parent::saveHistory($onlineCondition,'Update',$action,$courseName, $onlineCondition->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online condition "deleted" event.
     *
     * @param  \App\OnlineCondition  $onlineCondition
     * @return void
     */
    public function deleted(OnlineCourseCondition $onlineCondition)
    {
        $courseName = OnlineCourse::find($onlineCondition->course_id)->name;
        $action = 'Xóa điều kiện hoàn thành (khóa học online)';
        parent::saveHistory($onlineCondition,'Delete',$action,$courseName, $onlineCondition->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online condition "restored" event.
     *
     * @param  \App\OnlineCondition  $onlineCondition
     * @return void
     */
    public function restored(OnlineCourseCondition $onlineCondition)
    {
        //
    }

    /**
     * Handle the online condition "force deleted" event.
     *
     * @param  \App\OnlineCondition  $onlineCondition
     * @return void
     */
    public function forceDeleted(OnlineCourseCondition $onlineCondition)
    {
        //
    }
    private function updateCronCompleteCourseStatus(OnlineCourseCondition $onlineCondition){
        $exists = OnlineRegister::where(['course_id'=>$onlineCondition->course_id])->exists();
        if ($exists){
            OnlineRegister::where(['course_id'=>$onlineCondition->course_id])
                ->update([
                    'cron_complete' => 0
                ]);;

            OnlineRegisterView::where(['course_id'=>$onlineCondition->course_id])
                ->update([
                    'cron_complete' => 0
                ]);;
        }
    }
}
