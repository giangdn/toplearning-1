<?php

namespace App\Observers;


use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;

class OnlineCourseCostObserver extends BaseObserver
{
    /**
     * Handle the online course cost "created" event.
     *
     * @param  \App\OnlineCourseCost  $onlineCourseCost
     * @return void
     */
    public function created(OnlineCourseCost $onlineCourseCost)
    {
        $courseName = OnlineCourse::find($onlineCourseCost->course_id)->name;
        $action = 'Thêm chi phí đào tạo (khóa học online)';
        parent::saveHistory($onlineCourseCost,'Insert',$action,$courseName, $onlineCourseCost->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course cost "updated" event.
     *
     * @param  \App\OnlineCourseCost  $onlineCourseCost
     * @return void
     */
    public function updated(OnlineCourseCost $onlineCourseCost)
    {
        $courseName = OnlineCourse::find($onlineCourseCost->course_id)->name;
        $action = 'Cập nhật chi phí đào tạo (khóa học online)';
        parent::saveHistory($onlineCourseCost,'Update',$action,$courseName, $onlineCourseCost->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course cost "deleted" event.
     *
     * @param  \App\OnlineCourseCost  $onlineCourseCost
     * @return void
     */
    public function deleted(OnlineCourseCost $onlineCourseCost)
    {
        $courseName = OnlineCourse::find($onlineCourseCost->course_id)->name;
        $action = 'Xóa chi phí đào tạo (khóa học online)';
        parent::saveHistory($onlineCourseCost,'Delete',$action,$courseName, $onlineCourseCost->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course cost "restored" event.
     *
     * @param  \App\OnlineCourseCost  $onlineCourseCost
     * @return void
     */
    public function restored(OnlineCourseCost $onlineCourseCost)
    {
        //
    }

    /**
     * Handle the online course cost "force deleted" event.
     *
     * @param  \App\OnlineCourseCost  $onlineCourseCost
     * @return void
     */
    public function forceDeleted(OnlineCourseCost $onlineCourseCost)
    {
        //
    }
}
