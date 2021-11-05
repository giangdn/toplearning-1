<?php

namespace App\Observers;

use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizResult;

class QuizResultObserver extends BaseObserver
{
    /**
     * Handle the quiz result "created" event.
     *
     * @param  \App\QuizResult  $quizResult
     * @return void
     */
    public function created(QuizResult $quizResult)
    {
        $this->syncQuizResult($quizResult);
    }

    /**
     * Handle the quiz result "updated" event.
     *
     * @param  \App\QuizResult  $quizResult
     * @return void
     */
    public function updated(QuizResult $quizResult)
    {
        $this->syncQuizResult($quizResult);
    }

    /**
     * Handle the quiz result "deleted" event.
     *
     * @param  \App\QuizResult  $quizResult
     * @return void
     */
    public function deleted(QuizResult $quizResult)
    {
        //
    }

    /**
     * Handle the quiz result "restored" event.
     *
     * @param  \App\QuizResult  $quizResult
     * @return void
     */
    public function restored(QuizResult $quizResult)
    {
        //
    }

    /**
     * Handle the quiz result "force deleted" event.
     *
     * @param  \App\QuizResult  $quizResult
     * @return void
     */
    public function forceDeleted(QuizResult $quizResult)
    {
        //
    }
    private function syncQuizResult(QuizResult $quizResult){
        $quiz = Quiz::find($quizResult->quiz_id);
        if ($quiz->course_type==1){ // online
            $onlineRegister = OnlineRegister::where(['user_id'=>$quizResult->user_id,'course_id'=>$quiz->course_id])->first();
            if ($onlineRegister){
                $onlineRegister->cron_complete = 0;
                $onlineRegister->save();
            }
        }elseif ($quiz->course_type==2){// offline
            $offlineRegister = OfflineRegister::where(['user_id'=>$quizResult->user_id,'course_id'=>$quiz->course_id])->first();
            if ($offlineRegister){
                $offlineRegister->cron_complete = 0;
                $offlineRegister->save();
            }
        }
    }
}
