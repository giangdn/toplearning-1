<?php

namespace App\Observers;

use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseUpload;

class OfflineCourseUploadObserver extends BaseObserver
{
    /**
     * Handle the offline course upload "created" event.
     *
     * @param  \App\OfflineCourseUpload  $offlineCourseUpload
     * @return void
     */
    public function created(OfflineCourseUpload $offlineCourseUpload)
    {
        $courseName = OfflineCourse::find($offlineCourseUpload->course_id)->name;
        parent::saveHistory($offlineCourseUpload,'Insert','Thêm thư viện file (khóa học tập trung)',$courseName, $offlineCourseUpload->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline course upload "updated" event.
     *
     * @param  \App\OfflineCourseUpload  $offlineCourseUpload
     * @return void
     */
    public function updated(OfflineCourseUpload $offlineCourseUpload)
    {
        $courseName = OfflineCourse::find($offlineCourseUpload->course_id)->name;
        parent::saveHistory($offlineCourseUpload,'Update','Sửa thư viện file (khóa học tập trung)',$courseName, $offlineCourseUpload->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline course upload "deleted" event.
     *
     * @param  \App\OfflineCourseUpload  $offlineCourseUpload
     * @return void
     */
    public function deleted(OfflineCourseUpload $offlineCourseUpload)
    {
        $courseName = OfflineCourse::find($offlineCourseUpload->course_id)->name;
        parent::saveHistory($offlineCourseUpload,'Delete','Xóa thư viện file (khóa học tập trung)',$courseName, $offlineCourseUpload->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline course upload "restored" event.
     *
     * @param  \App\OfflineCourseUpload  $offlineCourseUpload
     * @return void
     */
    public function restored(OfflineCourseUpload $offlineCourseUpload)
    {
        //
    }

    /**
     * Handle the offline course upload "force deleted" event.
     *
     * @param  \App\OfflineCourseUpload  $offlineCourseUpload
     * @return void
     */
    public function forceDeleted(OfflineCourseUpload $offlineCourseUpload)
    {
        //
    }
}
