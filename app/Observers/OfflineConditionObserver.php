<?php

namespace App\Observers;

use Modules\Offline\Entities\OfflineCondition;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterView;

class OfflineConditionObserver extends BaseObserver
{
    /**
     * Handle the offline condition "created" event.
     *
     * @param  \App\OfflineCondition  $offlineCondition
     * @return void
     */
    public function created(OfflineCondition $offlineCondition)
    {
        $this->updateCronCompleteCourseStatus($offlineCondition);
        $courseName = OfflineCourse::find($offlineCondition->course_id)->name;
        parent::saveHistory($offlineCondition,'Insert','Thêm điều kiện hoàn thành (khóa học tập trung)',$courseName, $offlineCondition->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline condition "updated" event.
     *
     * @param  \App\OfflineCondition  $offlineCondition
     * @return void
     */
    public function updated(OfflineCondition $offlineCondition)
    {
        $this->updateCronCompleteCourseStatus($offlineCondition);
        $courseName = OfflineCourse::find($offlineCondition->course_id)->name;
        parent::saveHistory($offlineCondition,'Update','Sửa điều kiện hoàn thành (khóa học tập trung)',$courseName, $offlineCondition->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline condition "deleted" event.
     *
     * @param  \App\OfflineCondition  $offlineCondition
     * @return void
     */
    public function deleted(OfflineCondition $offlineCondition)
    {
        $courseName = OfflineCourse::find($offlineCondition->course_id)->name;
        parent::saveHistory($offlineCondition,'Delete','Xóa điều kiện hoàn thành (khóa học tập trung)',$courseName, $offlineCondition->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline condition "restored" event.
     *
     * @param  \App\OfflineCondition  $offlineCondition
     * @return void
     */
    public function restored(OfflineCondition $offlineCondition)
    {
        //
    }

    /**
     * Handle the offline condition "force deleted" event.
     *
     * @param  \App\OfflineCondition  $offlineCondition
     * @return void
     */
    public function forceDeleted(OfflineCondition $offlineCondition)
    {
        //
    }
    private function updateCronCompleteCourseStatus(OfflineCondition $offlineCondition){
        $onlineRegister = OfflineRegister::where(['course_id'=>$offlineCondition->course_id])->first();
        /*$onlineRegister->cron_complete = 0;
        $onlineRegister->save();*/
        if($onlineRegister){
            OfflineRegister::query()
                ->where(['course_id' => $offlineCondition->course_id])
                ->update([
                    'cron_complete' => 0
                ]);

            OfflineRegisterView::query()
                ->where(['course_id' => $offlineCondition->course_id])
                ->update([
                    'cron_complete' => 0
                ]);

        }
    }
}
