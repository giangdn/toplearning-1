<?php

namespace App\Observers;


use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseAskAnswer;

class OnlineCourseAskAnswerObserver extends BaseObserver
{
    /**
     * Handle the online course ask answer "created" event.
     *
     * @param  \App\OnlineCourseAskAnswer  $onlineCourseAskAnswer
     * @return void
     */
    public function created(OnlineCourseAskAnswer $onlineCourseAskAnswer)
    {
        $courseName = OnlineCourse::find($onlineCourseAskAnswer->course_id)->name;
        $action = 'Thêm hỏi/đáp (khóa học online)';
        parent::saveHistory($onlineCourseAskAnswer,'Insert',$action,$courseName, $onlineCourseAskAnswer->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course ask answer "updated" event.
     *
     * @param  \App\OnlineCourseAskAnswer  $onlineCourseAskAnswer
     * @return void
     */
    public function updated(OnlineCourseAskAnswer $onlineCourseAskAnswer)
    {
        $courseName = OnlineCourse::find($onlineCourseAskAnswer->course_id)->name;
        $action = 'Cập nhật hỏi/đáp (khóa học online)';
        parent::saveHistory($onlineCourseAskAnswer,'Update',$action,$courseName, $onlineCourseAskAnswer->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course ask answer "deleted" event.
     *
     * @param  \App\OnlineCourseAskAnswer  $onlineCourseAskAnswer
     * @return void
     */
    public function deleted(OnlineCourseAskAnswer $onlineCourseAskAnswer)
    {
        $courseName = OnlineCourse::find($onlineCourseAskAnswer->course_id)->name;
        $action = 'Xóa hỏi/đáp (khóa học online)';
        parent::saveHistory($onlineCourseAskAnswer,'Update',$action,$courseName, $onlineCourseAskAnswer->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online course ask answer "restored" event.
     *
     * @param  \App\OnlineCourseAskAnswer  $onlineCourseAskAnswer
     * @return void
     */
    public function restored(OnlineCourseAskAnswer $onlineCourseAskAnswer)
    {
        //
    }

    /**
     * Handle the online course ask answer "force deleted" event.
     *
     * @param  \App\OnlineCourseAskAnswer  $onlineCourseAskAnswer
     * @return void
     */
    public function forceDeleted(OnlineCourseAskAnswer $onlineCourseAskAnswer)
    {
        //
    }
}
