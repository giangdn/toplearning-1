<?php

namespace App\Observers;

use App\ProfileView;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;

class OfflineResultObserver extends BaseObserver
{
    /**
     * Handle the offline result "created" event.
     *
     * @param  \App\OfflineResult  $offlineResult
     * @return void
     */
    public function created(OfflineResult $offlineResult)
    {
        $this->updateCronCompleteCourseStatus($offlineResult);
        $courseName = OfflineCourse::find($offlineResult->course_id)->name;
        $user = ProfileView::find($offlineResult->user_id)->full_name;
        $action = 'Thêm kết quả đào tạo '.$user.' (khóa học tập trung)';
        parent::saveHistory($offlineResult,'Insert',$action,$courseName, $offlineResult->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline result "updated" event.
     *
     * @param  \App\OfflineResult  $offlineResult
     * @return void
     */
    public function updated(OfflineResult $offlineResult)
    {
        $this->updateCronCompleteCourseStatus($offlineResult);
        $courseName = OfflineCourse::find($offlineResult->course_id)->name;
        $user = ProfileView::find($offlineResult->user_id)->full_name;
        $action = 'Cập nhật kết quả đào tạo '.$user.' (khóa học tập trung)';
        parent::saveHistory($offlineResult,'Update',$action,$courseName, $offlineResult->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline result "deleted" event.
     *
     * @param  \App\OfflineResult  $offlineResult
     * @return void
     */
    public function deleted(OfflineResult $offlineResult)
    {
        $courseName = OfflineCourse::find($offlineResult->course_id)->name;
        $user = ProfileView::find($offlineResult->user_id)->full_name;
        $action = 'Xóa kết quả đào tạo '.$user.' (khóa học tập trung)';
        parent::saveHistory($offlineResult,'Delete',$action,$courseName, $offlineResult->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline result "restored" event.
     *
     * @param  \App\OfflineResult  $offlineResult
     * @return void
     */
    public function restored(OfflineResult $offlineResult)
    {
        //
    }

    /**
     * Handle the offline result "force deleted" event.
     *
     * @param  \App\OfflineResult  $offlineResult
     * @return void
     */
    public function forceDeleted(OfflineResult $offlineResult)
    {
        //
    }
    private function updateCronCompleteCourseStatus(OfflineResult $offlineResult){
        /*$onlineRegister = OfflineRegister::where(['user_id'=>$offlineResult->user_id,'course_id'=>$offlineResult->course_id])->first();
        $onlineRegister->cron_complete = 0;
        $onlineRegister->save();*/
    }
}
