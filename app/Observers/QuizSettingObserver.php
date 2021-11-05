<?php

namespace App\Observers;

use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizSetting;

class QuizSettingObserver extends BaseObserver
{
    /**
     * Handle the quiz setting "created" event.
     *
     * @param  \App\QuizSetting  $quizSetting
     * @return void
     */
    public function created(QuizSetting $quizSetting)
    {
        $quiz = Quiz::find($quizSetting->quiz_id)->name;
        $action = "Thêm cài đặt tùy chọn trong kỳ thi";
        parent::saveHistory($quizSetting,'Insert',$action,$quiz);
    }

    /**
     * Handle the quiz setting "updated" event.
     *
     * @param  \App\QuizSetting  $quizSetting
     * @return void
     */
    public function updated(QuizSetting $quizSetting)
    {
        $quiz = Quiz::find($quizSetting->quiz_id)->name;
        $action = "Cập nhật cài đặt tùy chọn trong kỳ thi";
        parent::saveHistory($quizSetting,'Update',$action,$quiz);
    }

    /**
     * Handle the quiz setting "deleted" event.
     *
     * @param  \App\QuizSetting  $quizSetting
     * @return void
     */
    public function deleted(QuizSetting $quizSetting)
    {
        $quiz = Quiz::find($quizSetting->quiz_id)->name;
        $action = "Xóa cài đặt tùy chọn trong kỳ thi";
        parent::saveHistory($quizSetting,'Delete',$action,$quiz);
    }

    /**
     * Handle the quiz setting "restored" event.
     *
     * @param  \App\QuizSetting  $quizSetting
     * @return void
     */
    public function restored(QuizSetting $quizSetting)
    {
        //
    }

    /**
     * Handle the quiz setting "force deleted" event.
     *
     * @param  \App\QuizSetting  $quizSetting
     * @return void
     */
    public function forceDeleted(QuizSetting $quizSetting)
    {
        //
    }
}
