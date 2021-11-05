<?php

namespace App\Observers;

use Modules\Quiz\Entities\Quiz;

class QuizObserver extends BaseObserver
{
    /**
     * Handle the quiz "created" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function created(Quiz $quiz)
    {
        $action = "Thêm kỳ thi ";
        parent::saveHistory($quiz,'Insert',$action);
    }

    /**
     * Handle the quiz "updated" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function updated(Quiz $quiz)
    {
        $action = "Cập nhật kỳ thi";
        if ($quiz->isDirty('approved_step'))
            $action = "Phê duyệt kỳ thi";
        parent::saveHistory($quiz,'Update',$action);
        if ($quiz->isDirty(['name']))
            $this->updateHasChange($quiz,1);
    }

    /**
     * Handle the quiz "deleted" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function deleted(Quiz $quiz)
    {
        $this->updateHasChange($quiz,2);
        $action = "Xóa kỳ thi ";
        parent::saveHistory($quiz,'Delete',$action);
    }

    /**
     * Handle the quiz "restored" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function restored(Quiz $quiz)
    {
        //
    }

    /**
     * Handle the quiz "force deleted" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function forceDeleted(Quiz $quiz)
    {
        //
    }
}
