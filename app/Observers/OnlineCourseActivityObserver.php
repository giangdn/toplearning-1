<?php

namespace App\Observers;

use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;

class OnlineCourseActivityObserver extends BaseObserver
{
    /**
     * Handle the online course activity "created" event.
     *
     * @param  \App\OnlineCourseActivity  $onlineCourseActivity
     * @return void
     */
    public function created(OnlineCourseActivity $onlineCourseActivity)
    {
        $courseName = OnlineCourse::find($onlineCourseActivity->course_id)->name;
        $action = 'Thêm hoạt động (khóa học online)';
        parent::saveHistory($onlineCourseActivity,'Insert',$action,$courseName, $onlineCourseActivity->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course activity "updated" event.
     *
     * @param  \App\OnlineCourseActivity  $onlineCourseActivity
     * @return void
     */
    public function updated(OnlineCourseActivity $onlineCourseActivity)
    {
        $courseName = OnlineCourse::find($onlineCourseActivity->course_id)->name;
        $action = 'Cập nhật hoạt động (khóa học online)';
        parent::saveHistory($onlineCourseActivity,'Update',$action,$courseName, $onlineCourseActivity->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course activity "deleted" event.
     *
     * @param  \App\OnlineCourseActivity  $onlineCourseActivity
     * @return void
     */
    public function deleted(OnlineCourseActivity $onlineCourseActivity)
    {
        $courseName = OnlineCourse::find($onlineCourseActivity->course_id)->name;
        $action = 'Xóa hoạt động (khóa học online)';
        parent::saveHistory($onlineCourseActivity,'Delete',$action,$courseName, $onlineCourseActivity->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course activity "restored" event.
     *
     * @param  \App\OnlineCourseActivity  $onlineCourseActivity
     * @return void
     */
    public function restored(OnlineCourseActivity $onlineCourseActivity)
    {
        //
    }

    /**
     * Handle the online course activity "force deleted" event.
     *
     * @param  \App\OnlineCourseActivity  $onlineCourseActivity
     * @return void
     */
    public function forceDeleted(OnlineCourseActivity $onlineCourseActivity)
    {
        //
    }
}
