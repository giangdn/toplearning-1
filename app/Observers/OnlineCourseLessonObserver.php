<?php

namespace App\Observers;


use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseLesson;

class OnlineCourseLessonObserver extends BaseObserver
{
    /**
     * Handle the online course lesson "created" event.
     *
     * @param  \App\OnlineCourseLesson  $onlineCourseLesson
     * @return void
     */
    public function created(OnlineCourseLesson $onlineCourseLesson)
    {
        $courseName = OnlineCourse::find($onlineCourseLesson->course_id)->name;
        $action = 'Thêm hoạt động/bài học (khóa học online)';
        parent::saveHistory($onlineCourseLesson,'Insert',$action,$courseName, $onlineCourseLesson->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course lesson "updated" event.
     *
     * @param  \App\OnlineCourseLesson  $onlineCourseLesson
     * @return void
     */
    public function updated(OnlineCourseLesson $onlineCourseLesson)
    {
        $courseName = OnlineCourse::find($onlineCourseLesson->course_id)->name;
        $action = 'Cập nhật hoạt động/bài học (khóa học online)';
        parent::saveHistory($onlineCourseLesson,'Update',$action,$courseName, $onlineCourseLesson->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course lesson "deleted" event.
     *
     * @param  \App\OnlineCourseLesson  $onlineCourseLesson
     * @return void
     */
    public function deleted(OnlineCourseLesson $onlineCourseLesson)
    {
        $courseName = OnlineCourse::find($onlineCourseLesson->course_id)->name;
        $action = 'Xóa hoạt động/bài học (khóa học online)';
        parent::saveHistory($onlineCourseLesson,'Delete',$action,$courseName, $onlineCourseLesson->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course lesson "restored" event.
     *
     * @param  \App\OnlineCourseLesson  $onlineCourseLesson
     * @return void
     */
    public function restored(OnlineCourseLesson $onlineCourseLesson)
    {
        //
    }

    /**
     * Handle the online course lesson "force deleted" event.
     *
     * @param  \App\OnlineCourseLesson  $onlineCourseLesson
     * @return void
     */
    public function forceDeleted(OnlineCourseLesson $onlineCourseLesson)
    {
        //
    }
}
