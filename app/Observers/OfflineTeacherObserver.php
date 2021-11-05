<?php

namespace App\Observers;


use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineTeacher;

class OfflineTeacherObserver extends BaseObserver
{
    /**
     * Handle the offline teacher "created" event.
     *
     * @param  \App\OfflineTeacher  $offlineTeacher
     * @return void
     */
    public function created(OfflineTeacher $offlineTeacher)
    {
        $courseName = OfflineCourse::find($offlineTeacher->course_id)->name;
        parent::saveHistory($offlineTeacher,'Insert','Thêm giảng viên (khóa học tập trung)',$courseName, $offlineTeacher->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline teacher "updated" event.
     *
     * @param  \App\OfflineTeacher  $offlineTeacher
     * @return void
     */
    public function updated(OfflineTeacher $offlineTeacher)
    {
        $courseName = OfflineCourse::find($offlineTeacher->course_id)->name;
        parent::saveHistory($offlineTeacher,'Update','Sửa giảng viên (khóa học tập trung)',$courseName, $offlineTeacher->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline teacher "deleted" event.
     *
     * @param  \App\OfflineTeacher  $offlineTeacher
     * @return void
     */
    public function deleted(OfflineTeacher $offlineTeacher)
    {
        $courseName = OfflineCourse::find($offlineTeacher->course_id)->name;
        parent::saveHistory($offlineTeacher,'Delete','Xóa giảng viên (khóa học tập trung)',$courseName, $offlineTeacher->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline teacher "restored" event.
     *
     * @param  \App\OfflineTeacher  $offlineTeacher
     * @return void
     */
    public function restored(OfflineTeacher $offlineTeacher)
    {
        //
    }

    /**
     * Handle the offline teacher "force deleted" event.
     *
     * @param  \App\OfflineTeacher  $offlineTeacher
     * @return void
     */
    public function forceDeleted(OfflineTeacher $offlineTeacher)
    {
        //
    }
}
