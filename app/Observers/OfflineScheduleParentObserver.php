<?php

namespace App\Observers;


use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineScheduleParent;

class OfflineScheduleParentObserver extends BaseObserver
{
    /**
     * Handle the offline schedule parent "created" event.
     *
     * @param  \App\OfflineScheduleParent  $offlineScheduleParent
     * @return void
     */
    public function created(OfflineScheduleParent $offlineScheduleParent)
    {
        $courseName = OfflineCourse::find($offlineScheduleParent->course_id)->name;
        parent::saveHistory($offlineScheduleParent,'Insert','Thêm lịch học (khóa học tập trung)',$courseName, $offlineScheduleParent->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline schedule parent "updated" event.
     *
     * @param  \App\OfflineScheduleParent  $offlineScheduleParent
     * @return void
     */
    public function updated(OfflineScheduleParent $offlineScheduleParent)
    {
        $courseName = OfflineCourse::find($offlineScheduleParent->course_id)->name;
        parent::saveHistory($offlineScheduleParent,'Update','Sửa lịch học (khóa học tập trung)',$courseName, $offlineScheduleParent->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline schedule parent "deleted" event.
     *
     * @param  \App\OfflineScheduleParent  $offlineScheduleParent
     * @return void
     */
    public function deleted(OfflineScheduleParent $offlineScheduleParent)
    {
        $courseName = OfflineCourse::find($offlineScheduleParent->course_id)->name;
        parent::saveHistory($offlineScheduleParent,'Delete','Xóa lịch học (khóa học tập trung)',$courseName, $offlineScheduleParent->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline schedule parent "restored" event.
     *
     * @param  \App\OfflineScheduleParent  $offlineScheduleParent
     * @return void
     */
    public function restored(OfflineScheduleParent $offlineScheduleParent)
    {
        //
    }

    /**
     * Handle the offline schedule parent "force deleted" event.
     *
     * @param  \App\OfflineScheduleParent  $offlineScheduleParent
     * @return void
     */
    public function forceDeleted(OfflineScheduleParent $offlineScheduleParent)
    {
        //
    }
}
