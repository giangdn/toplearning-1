<?php

namespace App\Observers;
use Modules\Quiz\Entities\QuizUserSecondary;

class QuizUserSecondaryObserver extends BaseObserver
{
    /**
     * Handle the quiz user secondary "created" event.
     *
     * @param  \App\QuizUserSecondary  $quizUserSecondary
     * @return void
     */
    public function created(QuizUserSecondary $quizUserSecondary)
    {
//        $quiz = Quiz::find($quizUserSecondary->quiz)
        $action = "Thêm thí sinh bên ngoài";
        parent::saveHistory($quizUserSecondary,'Insert',$action);
    }

    /**
     * Handle the quiz user secondary "updated" event.
     *
     * @param  \App\QuizUserSecondary  $quizUserSecondary
     * @return void
     */
    public function updated(QuizUserSecondary $quizUserSecondary)
    {
        $action = "Cập nhật thí sinh bên ngoài";
        parent::saveHistory($quizUserSecondary,'Update',$action);
    }

    /**
     * Handle the quiz user secondary "deleted" event.
     *
     * @param  \App\QuizUserSecondary  $quizUserSecondary
     * @return void
     */
    public function deleted(QuizUserSecondary $quizUserSecondary)
    {
        $action = "Xóa thí sinh bên ngoài";
        parent::saveHistory($quizUserSecondary,'Insert',$action);
    }

    /**
     * Handle the quiz user secondary "restored" event.
     *
     * @param  \App\QuizUserSecondary  $quizUserSecondary
     * @return void
     */
    public function restored(QuizUserSecondary $quizUserSecondary)
    {
        //
    }

    /**
     * Handle the quiz user secondary "force deleted" event.
     *
     * @param  \App\QuizUserSecondary  $quizUserSecondary
     * @return void
     */
    public function forceDeleted(QuizUserSecondary $quizUserSecondary)
    {
        //
    }
}
