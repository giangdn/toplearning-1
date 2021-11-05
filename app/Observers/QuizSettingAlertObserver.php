<?php

namespace App\Observers;

use App\ProfileView;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizSettingAlert;

class QuizSettingAlertObserver extends BaseObserver
{
    /**
     * Handle the quiz setting alert "created" event.
     *
     * @param  \App\QuizSettingAlert  $quizSettingAlert
     * @return void
     */
    public function created(QuizSettingAlert $quizSettingAlert)
    {
        $action = "Thêm thiết lập cảnh báo kỳ thi";
        parent::saveHistory($quizSettingAlert,'Insert',$action,'kỳ thi');
    }

    /**
     * Handle the quiz setting alert "updated" event.
     *
     * @param  \App\QuizSettingAlert  $quizSettingAlert
     * @return void
     */
    public function updated(QuizSettingAlert $quizSettingAlert)
    {
        $action = "Cập nhật thiết lập cảnh báo kỳ thi";
        parent::saveHistory($quizSettingAlert,'Update',$action,'kỳ thi');
    }

    /**
     * Handle the quiz setting alert "deleted" event.
     *
     * @param  \App\QuizSettingAlert  $quizSettingAlert
     * @return void
     */
    public function deleted(QuizSettingAlert $quizSettingAlert)
    {
        $action = "Xóa thiết lập cảnh báo kỳ thi";
        parent::saveHistory($quizSettingAlert,'Delete',$action,'kỳ thi');
    }

    /**
     * Handle the quiz setting alert "restored" event.
     *
     * @param  \App\QuizSettingAlert  $quizSettingAlert
     * @return void
     */
    public function restored(QuizSettingAlert $quizSettingAlert)
    {
        //
    }

    /**
     * Handle the quiz setting alert "force deleted" event.
     *
     * @param  \App\QuizSettingAlert  $quizSettingAlert
     * @return void
     */
    public function forceDeleted(QuizSettingAlert $quizSettingAlert)
    {
        //
    }
}
