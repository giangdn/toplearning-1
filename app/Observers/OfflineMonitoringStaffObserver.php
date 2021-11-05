<?php

namespace App\Observers;


use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineMonitoringStaff;

class OfflineMonitoringStaffObserver extends BaseObserver
{
    /**
     * Handle the offline monitoring staff "created" event.
     *
     * @param  \App\OfflineMonitoringStaff  $offlineMonitoringStaff
     * @return void
     */
    public function created(OfflineMonitoringStaff $offlineMonitoringStaff)
    {
        $courseName = OfflineCourse::find($offlineMonitoringStaff->course_id)->name;
        parent::saveHistory($offlineMonitoringStaff,'Insert','Thêm cán bộ theo dõi (khóa học tập trung)',$courseName, $offlineMonitoringStaff->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline monitoring staff "updated" event.
     *
     * @param  \App\OfflineMonitoringStaff  $offlineMonitoringStaff
     * @return void
     */
    public function updated(OfflineMonitoringStaff $offlineMonitoringStaff)
    {
        $courseName = OfflineCourse::find($offlineMonitoringStaff->course_id)->name;
        parent::saveHistory($offlineMonitoringStaff,'Update','Sửa cán bộ theo dõi (khóa học tập trung)',$courseName, $offlineMonitoringStaff->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline monitoring staff "deleted" event.
     *
     * @param  \App\OfflineMonitoringStaff  $offlineMonitoringStaff
     * @return void
     */
    public function deleted(OfflineMonitoringStaff $offlineMonitoringStaff)
    {
        $courseName = OfflineCourse::find($offlineMonitoringStaff->course_id)->name;
        parent::saveHistory($offlineMonitoringStaff,'Delete','Xóa cán bộ theo dõi (khóa học tập trung)',$courseName, $offlineMonitoringStaff->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline monitoring staff "restored" event.
     *
     * @param  \App\OfflineMonitoringStaff  $offlineMonitoringStaff
     * @return void
     */
    public function restored(OfflineMonitoringStaff $offlineMonitoringStaff)
    {
        //
    }

    /**
     * Handle the offline monitoring staff "force deleted" event.
     *
     * @param  \App\OfflineMonitoringStaff  $offlineMonitoringStaff
     * @return void
     */
    public function forceDeleted(OfflineMonitoringStaff $offlineMonitoringStaff)
    {
        //
    }
}
