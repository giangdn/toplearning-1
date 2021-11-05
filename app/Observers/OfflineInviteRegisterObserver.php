<?php

namespace App\Observers;

use App\ProfileView;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineInviteRegister;

class OfflineInviteRegisterObserver extends BaseObserver
{
    /**
     * Handle the offline invite register "created" event.
     *
     * @param  \App\OfflineInviteRegister  $offlineInviteRegister
     * @return void
     */
    public function created(OfflineInviteRegister $offlineInviteRegister)
    {
        $courseName = OfflineCourse::find($offlineInviteRegister->course_id)->name;
        $student = ProfileView::find($offlineInviteRegister->user_id)->full_name;
        $action = 'Thêm mời ghi danh '.$student.' (khóa học tập trung)';
        parent::saveHistory($offlineInviteRegister,'Insert',$action,$courseName, $offlineInviteRegister->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline invite register "updated" event.
     *
     * @param  \App\OfflineInviteRegister  $offlineInviteRegister
     * @return void
     */
    public function updated(OfflineInviteRegister $offlineInviteRegister)
    {
        $courseName = OfflineCourse::find($offlineInviteRegister->course_id)->name;
        $student = ProfileView::find($offlineInviteRegister->user_id)->full_name;
        $action = 'Sửa mời ghi danh '.$student.' (khóa học tập trung)';
        parent::saveHistory($offlineInviteRegister,'Update',$action,$courseName, $offlineInviteRegister->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline invite register "deleted" event.
     *
     * @param  \App\OfflineInviteRegister  $offlineInviteRegister
     * @return void
     */
    public function deleted(OfflineInviteRegister $offlineInviteRegister)
    {
        $courseName = OfflineCourse::find($offlineInviteRegister->course_id)->name;
        $student = ProfileView::find($offlineInviteRegister->user_id)->full_name;
        $action = 'Xóa mời ghi danh '.$student.' (khóa học tập trung)';
        parent::saveHistory($offlineInviteRegister,'Delete',$action,$courseName, $offlineInviteRegister->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline invite register "restored" event.
     *
     * @param  \App\OfflineInviteRegister  $offlineInviteRegister
     * @return void
     */
    public function restored(OfflineInviteRegister $offlineInviteRegister)
    {
        //
    }

    /**
     * Handle the offline invite register "force deleted" event.
     *
     * @param  \App\OfflineInviteRegister  $offlineInviteRegister
     * @return void
     */
    public function forceDeleted(OfflineInviteRegister $offlineInviteRegister)
    {
        //
    }
}
