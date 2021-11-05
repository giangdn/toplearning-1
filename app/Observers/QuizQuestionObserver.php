<?php

namespace App\Observers;

use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizQuestion;

class QuizQuestionObserver extends BaseObserver
{
    /**
     * Handle the quiz question "created" event.
     *
     * @param  \App\QuizQuestion  $quizQuestion
     * @return void
     */
    public function created(QuizQuestion $quizQuestion)
    {
        $quiz = Quiz::find($quizQuestion->quiz_id)->name;
        $action = "Thêm câu hỏi vào kỳ thi";
        parent::saveHistory($quizQuestion,'Insert',$action,$quiz);
    }

    /**
     * Handle the quiz question "updated" event.
     *
     * @param  \App\QuizQuestion  $quizQuestion
     * @return void
     */
    public function updated(QuizQuestion $quizQuestion)
    {
        $quiz = Quiz::find($quizQuestion->quiz_id)->name;
        $action = "Cập nhật câu hỏi trong kỳ thi";
        parent::saveHistory($quizQuestion,'Update',$action,$quiz);
    }

    /**
     * Handle the quiz question "deleted" event.
     *
     * @param  \App\QuizQuestion  $quizQuestion
     * @return void
     */
    public function deleted(QuizQuestion $quizQuestion)
    {
        $quiz = Quiz::find($quizQuestion->quiz_id)->name;
        $action = "Xóa câu hỏi trong kỳ thi";
        parent::saveHistory($quizQuestion,'Delete',$action,$quiz);
    }

    /**
     * Handle the quiz question "restored" event.
     *
     * @param  \App\QuizQuestion  $quizQuestion
     * @return void
     */
    public function restored(QuizQuestion $quizQuestion)
    {
        //
    }

    /**
     * Handle the quiz question "force deleted" event.
     *
     * @param  \App\QuizQuestion  $quizQuestion
     * @return void
     */
    public function forceDeleted(QuizQuestion $quizQuestion)
    {
        //
    }
}
