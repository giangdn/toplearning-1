<?php

namespace App\Observers;


use App\ProfileView;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterView;

class OfflineAttendanceObserver extends BaseObserver
{
    /**
     * Handle the offline attendance "created" event.
     *
     * @param  \App\OfflineAttendance  $offlineAttendance
     * @return void
     */
    public function created(OfflineAttendance $offlineAttendance)
    {
        $courseName = OfflineCourse::find($offlineAttendance->course_id)->name;
        $user = ProfileView::find($offlineAttendance->user_id)->full_name;
        $action = 'Thêm điểm danh '.$user.' (khóa học tập trung)';
        parent::saveHistory($offlineAttendance,'Insert',$action,$courseName, $offlineAttendance->course_id,app(OfflineCourse::class)->getTable());

        OfflineRegister::query()
            ->where(['course_id' => $offlineAttendance->course_id])
            ->where(['user_id' => $offlineAttendance->user_id])
            ->update([
                'cron_complete' => 0
            ]);

        OfflineRegisterView::query()
            ->where(['course_id' => $offlineAttendance->course_id])
            ->where(['user_id' => $offlineAttendance->user_id])
            ->update([
                'cron_complete' => 0
            ]);
    }

    /**
     * Handle the offline attendance "updated" event.
     *
     * @param  \App\OfflineAttendance  $offlineAttendance
     * @return void
     */
    public function updated(OfflineAttendance $offlineAttendance)
    {
        $courseName = OfflineCourse::find($offlineAttendance->course_id)->name;
        $user = ProfileView::find($offlineAttendance->user_id)->full_name;
        $action = 'Cập nhật điểm danh '.$user.' (khóa học tập trung)';
        parent::saveHistory($offlineAttendance,'Update',$action,$courseName, $offlineAttendance->course_id,app(OfflineCourse::class)->getTable());

        OfflineRegister::query()
            ->where(['course_id' => $offlineAttendance->course_id])
            ->where(['user_id' => $offlineAttendance->user_id])
            ->update([
                'cron_complete' => 0
            ]);

        OfflineRegisterView::query()
            ->where(['course_id' => $offlineAttendance->course_id])
            ->where(['user_id' => $offlineAttendance->user_id])
            ->update([
                'cron_complete' => 0
            ]);
    }

    /**
     * Handle the offline attendance "deleted" event.
     *
     * @param  \App\OfflineAttendance  $offlineAttendance
     * @return void
     */
    public function deleted(OfflineAttendance $offlineAttendance)
    {
        $courseName = OfflineCourse::find($offlineAttendance->course_id)->name;
        $user = ProfileView::find($offlineAttendance->user_id)->full_name;
        $action = 'Xóa điểm danh '.$user.' (khóa học tập trung)';
        parent::saveHistory($offlineAttendance,'Delete',$action,$courseName, $offlineAttendance->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline attendance "restored" event.
     *
     * @param  \App\OfflineAttendance  $offlineAttendance
     * @return void
     */
    public function restored(OfflineAttendance $offlineAttendance)
    {
        //
    }

    /**
     * Handle the offline attendance "force deleted" event.
     *
     * @param  \App\OfflineAttendance  $offlineAttendance
     * @return void
     */
    public function forceDeleted(OfflineAttendance $offlineAttendance)
    {
        //
    }
}
