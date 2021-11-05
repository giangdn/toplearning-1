<?php

namespace App\Observers;
use App\ProfileView;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizUserSecondary;

class QuizRegisterObserver extends BaseObserver
{
    /**
     * Handle the quiz register "created" event.
     *
     * @param  \App\QuizRegister  $quizRegister
     * @return void
     */
    public function created(QuizRegister $quizRegister)
    {
        $quiz = Quiz::find($quizRegister->quiz_id)->name;
        if ($quizRegister->type == 1){
            $student = ProfileView::find($quizRegister->user_id)->full_name;
            $action = "Thêm ghi danh thí sinh ".$student." vào kỳ thi";
        }else{
            $student = QuizUserSecondary::find($quizRegister->user_id)->name;
            $action = "Thêm ghi danh thí sinh ngoài ".$student." vào kỳ thi";
        }

        parent::saveHistory($quizRegister,'Insert',$action,$quiz);
    }

    /**
     * Handle the quiz register "updated" event.
     *
     * @param  \App\QuizRegister  $quizRegister
     * @return void
     */
    public function updated(QuizRegister $quizRegister)
    {
        $quiz = Quiz::find($quizRegister->quiz_id)->name;
        if ($quizRegister->type == 1){
            $student = ProfileView::find($quizRegister->user_id)->full_name;
            $action = "Cập nhật ghi danh thí sinh ".$student." trong kỳ thi";
        } else{
            $student = QuizUserSecondary::find($quizRegister->user_id)->name;
            $action = "Cập nhật ghi danh thí sinh ngoài ".$student." trong kỳ thi";
        }

        parent::saveHistory($quizRegister,'Update',$action,$quiz);
    }

    /**
     * Handle the quiz register "deleted" event.
     *
     * @param  \App\QuizRegister  $quizRegister
     * @return void
     */
    public function deleted(QuizRegister $quizRegister)
    {
        $quiz = Quiz::find($quizRegister->quiz_id)->name;
        if ($quizRegister->type == 1){
            $student = ProfileView::find($quizRegister->user_id)->full_name;
            $action = "Xóa ghi danh thí sinh ".$student." trong kỳ thi";
        }else{
            $student = QuizUserSecondary::find($quizRegister->user_id)->name;
            $action = "Xóa ghi danh thí sinh ngoài ".$student." trong kỳ thi";
        }

        parent::saveHistory($quizRegister,'Insert',$action,$quiz);
    }

    /**
     * Handle the quiz register "restored" event.
     *
     * @param  \App\QuizRegister  $quizRegister
     * @return void
     */
    public function restored(QuizRegister $quizRegister)
    {
        //
    }

    /**
     * Handle the quiz register "force deleted" event.
     *
     * @param  \App\QuizRegister  $quizRegister
     * @return void
     */
    public function forceDeleted(QuizRegister $quizRegister)
    {
        //
    }
}
