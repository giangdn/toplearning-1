<?php

namespace App\Observers;


use Modules\Quiz\Entities\QuizTemplates;

class QuizTemplatesObserver extends BaseObserver
{
    /**
     * Handle the quiz templates "created" event.
     *
     * @param  \App\QuizTemplates  $quizTemplates
     * @return void
     */
    public function created(QuizTemplates $quizTemplates)
    {
        $action = "Thêm cơ cấu đề thi ";
        parent::saveHistory($quizTemplates,'Insert',$action);
    }

    /**
     * Handle the quiz templates "updated" event.
     *
     * @param  \App\QuizTemplates  $quizTemplates
     * @return void
     */
    public function updated(QuizTemplates $quizTemplates)
    {
        $action = "Cập nhật cơ cấu đề thi ";
        if ($quizTemplates->isDirty('approved_step'))
            $action = "Phê duyệt cơ cấu đề thi ";
        parent::saveHistory($quizTemplates,'Update',$action);
    }

    /**
     * Handle the quiz templates "deleted" event.
     *
     * @param  \App\QuizTemplates  $quizTemplates
     * @return void
     */
    public function deleted(QuizTemplates $quizTemplates)
    {
        $action = "Xóa cơ cấu đề thi ";
        parent::saveHistory($quizTemplates,'Insert',$action);
    }

    /**
     * Handle the quiz templates "restored" event.
     *
     * @param  \App\QuizTemplates  $quizTemplates
     * @return void
     */
    public function restored(QuizTemplates $quizTemplates)
    {
        //
    }

    /**
     * Handle the quiz templates "force deleted" event.
     *
     * @param  \App\QuizTemplates  $quizTemplates
     * @return void
     */
    public function forceDeleted(QuizTemplates $quizTemplates)
    {
        //
    }
}
