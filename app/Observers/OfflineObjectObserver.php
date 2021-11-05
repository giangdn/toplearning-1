<?php

namespace App\Observers;


use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineObject;

class OfflineObjectObserver extends BaseObserver
{
    /**
     * Handle the offline object "created" event.
     *
     * @param  \App\OfflineObject  $offlineObject
     * @return void
     */
    public function created(OfflineObject $offlineObject)
    {
        $courseName = OfflineCourse::find($offlineObject->course_id)->name;
        parent::saveHistory($offlineObject,'Insert','Thêm đối tượng tham gia (khóa học tập trung)',$courseName, $offlineObject->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline object "updated" event.
     *
     * @param  \App\OfflineObject  $offlineObject
     * @return void
     */
    public function updated(OfflineObject $offlineObject)
    {
        $courseName = OfflineCourse::find($offlineObject->course_id)->name;
        parent::saveHistory($offlineObject,'Update','Sửa đối tượng tham gia (khóa học tập trung)',$courseName, $offlineObject->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline object "deleted" event.
     *
     * @param  \App\OfflineObject  $offlineObject
     * @return void
     */
    public function deleted(OfflineObject $offlineObject)
    {
        $courseName = OfflineCourse::find($offlineObject->course_id)->name;
        parent::saveHistory($offlineObject,'Delete','Xóa đối tượng tham gia (khóa học tập trung)',$courseName, $offlineObject->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline object "restored" event.
     *
     * @param  \App\OfflineObject  $offlineObject
     * @return void
     */
    public function restored(OfflineObject $offlineObject)
    {
        //
    }

    /**
     * Handle the offline object "force deleted" event.
     *
     * @param  \App\OfflineObject  $offlineObject
     * @return void
     */
    public function forceDeleted(OfflineObject $offlineObject)
    {
        //
    }
}
