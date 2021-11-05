<?php

namespace App\Observers;


use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;

class OfflineCourseCostObserver extends BaseObserver
{
    /**
     * Handle the offline course cost "created" event.
     *
     * @param  \App\OfflineCourseCost  $offlineCourseCost
     * @return void
     */
    public function created(OfflineCourseCost $offlineCourseCost)
    {
        $courseName = OfflineCourse::find($offlineCourseCost->course_id)->name;
        parent::saveHistory($offlineCourseCost,'Insert','Thêm chi phí đào tạo (khóa học tập trung)',$courseName, $offlineCourseCost->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline course cost "updated" event.
     *
     * @param  \App\OfflineCourseCost  $offlineCourseCost
     * @return void
     */
    public function updated(OfflineCourseCost $offlineCourseCost)
    {
        $courseName = OfflineCourse::find($offlineCourseCost->course_id)->name;
        parent::saveHistory($offlineCourseCost,'Update','Sửa chi phí đào tạo (khóa học tập trung)',$courseName, $offlineCourseCost->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline course cost "deleted" event.
     *
     * @param  \App\OfflineCourseCost  $offlineCourseCost
     * @return void
     */
    public function deleted(OfflineCourseCost $offlineCourseCost)
    {
        $courseName = OfflineCourse::find($offlineCourseCost->course_id)->name;
        parent::saveHistory($offlineCourseCost,'Delete','Xóa chi phí đào tạo (khóa học tập trung)',$courseName, $offlineCourseCost->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the offline course cost "restored" event.
     *
     * @param  \App\OfflineCourseCost  $offlineCourseCost
     * @return void
     */
    public function restored(OfflineCourseCost $offlineCourseCost)
    {
        //
    }

    /**
     * Handle the offline course cost "force deleted" event.
     *
     * @param  \App\OfflineCourseCost  $offlineCourseCost
     * @return void
     */
    public function forceDeleted(OfflineCourseCost $offlineCourseCost)
    {
        //
    }
}
