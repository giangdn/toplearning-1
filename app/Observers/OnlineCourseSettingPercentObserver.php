<?php

namespace App\Observers;


use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseSettingPercent;

class OnlineCourseSettingPercentObserver extends BaseObserver
{
    /**
     * Handle the online course setting percent "created" event.
     *
     * @param  \App\OnlineCourseSettingPercent  $onlineCourseSettingPercent
     * @return void
     */
    public function created(OnlineCourseSettingPercent $onlineCourseSettingPercent)
    {
        $courseName = @OnlineCourse::find($onlineCourseSettingPercent->course_id)->name;
        $action = 'Thêm thiết lập thông số hoạt động (khóa học online)';
        parent::saveHistory($onlineCourseSettingPercent,'Insert',$action,$courseName, $onlineCourseSettingPercent->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course setting percent "updated" event.
     *
     * @param  \App\OnlineCourseSettingPercent  $onlineCourseSettingPercent
     * @return void
     */
    public function updated(OnlineCourseSettingPercent $onlineCourseSettingPercent)
    {
        $course = OnlineCourse::find($onlineCourseSettingPercent->course_id);
        if ($course){
            $courseName = $course->name;
            $action = 'Cập nhật thiết lập thông số hoạt động (khóa học online)';
            parent::saveHistory($onlineCourseSettingPercent,'Update',$action,$courseName, $onlineCourseSettingPercent->course_id,app(OnlineCourse::class)->getTable());
        }
    }

    /**
     * Handle the online course setting percent "deleted" event.
     *
     * @param  \App\OnlineCourseSettingPercent  $onlineCourseSettingPercent
     * @return void
     */
    public function deleted(OnlineCourseSettingPercent $onlineCourseSettingPercent)
    {
        $courseName = @OnlineCourse::find($onlineCourseSettingPercent->course_id)->name;
        $action = 'Xóa thiết lập thông số hoạt động (khóa học online)';
        parent::saveHistory($onlineCourseSettingPercent,'Delete',$action,$courseName, $onlineCourseSettingPercent->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course setting percent "restored" event.
     *
     * @param  \App\OnlineCourseSettingPercent  $onlineCourseSettingPercent
     * @return void
     */
    public function restored(OnlineCourseSettingPercent $onlineCourseSettingPercent)
    {
        //
    }

    /**
     * Handle the online course setting percent "force deleted" event.
     *
     * @param  \App\OnlineCourseSettingPercent  $onlineCourseSettingPercent
     * @return void
     */
    public function forceDeleted(OnlineCourseSettingPercent $onlineCourseSettingPercent)
    {
        //
    }
}
