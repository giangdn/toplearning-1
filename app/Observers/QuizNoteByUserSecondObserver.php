<?php

namespace App\Observers;

use App\ProfileView;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizNoteByUserSecond;

class QuizNoteByUserSecondObserver extends BaseObserver
{
    /**
     * Handle the quiz note by user second "created" event.
     *
     * @param  \App\QuizNoteByUserSecond  $quizNoteByUserSecond
     * @return void
     */
    public function created(QuizNoteByUserSecond $quizNoteByUserSecond)
    {
        $quiz = Quiz::find($quizNoteByUserSecond->quiz_id)->name;
        $student = ProfileView::find($quizNoteByUserSecond->user_id)->full_name;
        $action = "Thêm điều chỉnh thông tin thi học viên ".$student;
        parent::saveHistory($quizNoteByUserSecond,'Insert',$action,$quiz);
    }

    /**
     * Handle the quiz note by user second "updated" event.
     *
     * @param  \App\QuizNoteByUserSecond  $quizNoteByUserSecond
     * @return void
     */
    public function updated(QuizNoteByUserSecond $quizNoteByUserSecond)
    {
        $quiz = Quiz::find($quizNoteByUserSecond->quiz_id)->name;
        $student = ProfileView::find($quizNoteByUserSecond->user_id)->full_name;
        $action = "Cập nhật điều chỉnh thông tin thi học viên ".$student;
        parent::saveHistory($quizNoteByUserSecond,'Update',$action,$quiz);
    }

    /**
     * Handle the quiz note by user second "deleted" event.
     *
     * @param  \App\QuizNoteByUserSecond  $quizNoteByUserSecond
     * @return void
     */
    public function deleted(QuizNoteByUserSecond $quizNoteByUserSecond)
    {
        $quiz = Quiz::find($quizNoteByUserSecond->quiz_id)->name;
        $student = ProfileView::find($quizNoteByUserSecond->user_id)->full_name;
        $action = "Xóa điều chỉnh thông tin thi học viên ".$student;
        parent::saveHistory($quizNoteByUserSecond,'Delete',$action,$quiz);
    }

    /**
     * Handle the quiz note by user second "restored" event.
     *
     * @param  \App\QuizNoteByUserSecond  $quizNoteByUserSecond
     * @return void
     */
    public function restored(QuizNoteByUserSecond $quizNoteByUserSecond)
    {
        //
    }

    /**
     * Handle the quiz note by user second "force deleted" event.
     *
     * @param  \App\QuizNoteByUserSecond  $quizNoteByUserSecond
     * @return void
     */
    public function forceDeleted(QuizNoteByUserSecond $quizNoteByUserSecond)
    {
        //
    }
}
