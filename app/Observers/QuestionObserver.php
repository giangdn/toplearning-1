<?php

namespace App\Observers;

use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionCategory;

class QuestionObserver extends BaseObserver
{
    /**
     * Handle the question "created" event.
     *
     * @param  \App\Question  $question
     * @return void
     */
    public function created(Question $question)
    {
        $cate = QuestionCategory::find($question->category_id)->name;
        $action = "Thêm câu hỏi ngân hàng câu hỏi ".$cate;
        parent::saveHistory($question,'Insert',$action);
    }

    /**
     * Handle the question "updated" event.
     *
     * @param  \App\Question  $question
     * @return void
     */
    public function updated(Question $question)
    {
        $cate = QuestionCategory::find($question->category_id)->name;
        $action = "Cập nhật câu hỏi ngân hàng câu hỏi ".$cate;
        if ($question->isDirty('status'))
            $action = "Phê/duyệt câu hỏi ngân hàng câu hỏi ".$cate;
        parent::saveHistory($question,'Update',$action);
    }

    /**
     * Handle the question "deleted" event.
     *
     * @param  \App\Question  $question
     * @return void
     */
    public function deleted(Question $question)
    {
        $cate = QuestionCategory::find($question->category_id)->name;
        $action = "Xóa câu hỏi ngân hàng câu hỏi ".$cate;
        parent::saveHistory($question,'Delete',$action);
    }

    /**
     * Handle the question "restored" event.
     *
     * @param  \App\Question  $question
     * @return void
     */
    public function restored(Question $question)
    {
        //
    }

    /**
     * Handle the question "force deleted" event.
     *
     * @param  \App\Question  $question
     * @return void
     */
    public function forceDeleted(Question $question)
    {
        //
    }
}
