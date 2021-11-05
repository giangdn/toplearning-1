<?php

namespace App\Observers;

use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizTeacher;

class QuizTeacherObserver extends BaseObserver
{
    /**
     * Handle the quiz teacher "created" event.
     *
     * @param  \App\QuizTeacher  $quizTeacher
     * @return void
     */
    public function created(QuizTeacher $quizTeacher)
    {
        $quiz = Quiz::find($quizTeacher->quiz_id)->name;
        $action = "Thêm giảng viên vào kỳ thi";
        parent::saveHistory($quizTeacher,'Update',$action,$quiz);
    }

    /**
     * Handle the quiz teacher "updated" event.
     *
     * @param  \App\QuizTeacher  $quizTeacher
     * @return void
     */
    public function updated(QuizTeacher $quizTeacher)
    {
        $quiz = Quiz::find($quizTeacher->quiz_id)->name;
        $action = "Cập nhật giảng viên trong kỳ thi";
        parent::saveHistory($quizTeacher,'Update',$action,$quiz);
    }

    /**
     * Handle the quiz teacher "deleted" event.
     *
     * @param  \App\QuizTeacher  $quizTeacher
     * @return void
     */
    public function deleted(QuizTeacher $quizTeacher)
    {
        $quiz = Quiz::find($quizTeacher->quiz_id)->name;
        $action = "Xóa giảng viên trong kỳ thi";
        parent::saveHistory($quizTeacher,'Delete',$action,$quiz);
    }

    /**
     * Handle the quiz teacher "restored" event.
     *
     * @param  \App\QuizTeacher  $quizTeacher
     * @return void
     */
    public function restored(QuizTeacher $quizTeacher)
    {
        //
    }

    /**
     * Handle the quiz teacher "force deleted" event.
     *
     * @param  \App\QuizTeacher  $quizTeacher
     * @return void
     */
    public function forceDeleted(QuizTeacher $quizTeacher)
    {
        //
    }
}
