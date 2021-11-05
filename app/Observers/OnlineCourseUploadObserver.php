<?php

namespace App\Observers;


use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseUpload;

class OnlineCourseUploadObserver extends BaseObserver
{
    /**
     * Handle the online course upload "created" event.
     *
     * @param  \App\OnlineCourseUpload  $onlineCourseUpload
     * @return void
     */
    public function created(OnlineCourseUpload $onlineCourseUpload)
    {
        $courseName = OnlineCourse::find($onlineCourseUpload->course_id)->name;
        $action = 'Thêm thư viện file (khóa học online)';
        parent::saveHistory($onlineCourseUpload,'Insert',$action,$courseName, $onlineCourseUpload->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course upload "updated" event.
     *
     * @param  \App\OnlineCourseUpload  $onlineCourseUpload
     * @return void
     */
    public function updated(OnlineCourseUpload $onlineCourseUpload)
    {
        $courseName = OnlineCourse::find($onlineCourseUpload->course_id)->name;
        $action = 'Cập nhật thư viện file (khóa học online)';
        parent::saveHistory($onlineCourseUpload,'Update',$action,$courseName, $onlineCourseUpload->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course upload "deleted" event.
     *
     * @param  \App\OnlineCourseUpload  $onlineCourseUpload
     * @return void
     */
    public function deleted(OnlineCourseUpload $onlineCourseUpload)
    {
        $courseName = OnlineCourse::find($onlineCourseUpload->course_id)->name;
        $action = 'Xóa thư viện file (khóa học online)';
        parent::saveHistory($onlineCourseUpload,'Delete',$action,$courseName, $onlineCourseUpload->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course upload "restored" event.
     *
     * @param  \App\OnlineCourseUpload  $onlineCourseUpload
     * @return void
     */
    public function restored(OnlineCourseUpload $onlineCourseUpload)
    {
        //
    }

    /**
     * Handle the online course upload "force deleted" event.
     *
     * @param  \App\OnlineCourseUpload  $onlineCourseUpload
     * @return void
     */
    public function forceDeleted(OnlineCourseUpload $onlineCourseUpload)
    {
        //
    }
}
