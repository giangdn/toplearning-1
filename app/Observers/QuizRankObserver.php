<?php

namespace App\Observers;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRank;

class QuizRankObserver extends BaseObserver
{
    /**
     * Handle the quiz rank "created" event.
     *
     * @param  \App\QuizRank  $quizRank
     * @return void
     */
    public function created(QuizRank $quizRank)
    {
        $quiz = Quiz::find($quizRank->quiz_id)->name;
        $action = "Thêm xếp loại trong kỳ thi";
        parent::saveHistory($quizRank,'Insert',$action,$quiz);
    }

    /**
     * Handle the quiz rank "updated" event.
     *
     * @param  \App\QuizRank  $quizRank
     * @return void
     */
    public function updated(QuizRank $quizRank)
    {
        $quiz = Quiz::find($quizRank->quiz_id)->name;
        $action = "Cập nhật xếp loại trong kỳ thi";
        parent::saveHistory($quizRank,'Update',$action,$quiz);
    }

    /**
     * Handle the quiz rank "deleted" event.
     *
     * @param  \App\QuizRank  $quizRank
     * @return void
     */
    public function deleted(QuizRank $quizRank)
    {
        $quiz = Quiz::find($quizRank->quiz_id)->name;
        $action = "Xóa xếp loại trong kỳ thi";
        parent::saveHistory($quizRank,'Delete',$action,$quiz);
    }

    /**
     * Handle the quiz rank "restored" event.
     *
     * @param  \App\QuizRank  $quizRank
     * @return void
     */
    public function restored(QuizRank $quizRank)
    {
        //
    }

    /**
     * Handle the quiz rank "force deleted" event.
     *
     * @param  \App\QuizRank  $quizRank
     * @return void
     */
    public function forceDeleted(QuizRank $quizRank)
    {
        //
    }
}
