<?php

namespace App\Observers;


use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesRank;

class QuizTemplatesRankObserver extends BaseObserver
{
    /**
     * Handle the quiz templates rank "created" event.
     *
     * @param  \App\QuizTemplatesRank  $quizTemplatesRank
     * @return void
     */
    public function created(QuizTemplatesRank $quizTemplatesRank)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesRank->quiz_id)->name;
        $action = "Thêm xếp loại cơ cấu đề thi ";
        parent::saveHistory($quizTemplatesRank,'Insert',$action,$quizTemplate);
    }

    /**
     * Handle the quiz templates rank "updated" event.
     *
     * @param  \App\QuizTemplatesRank  $quizTemplatesRank
     * @return void
     */
    public function updated(QuizTemplatesRank $quizTemplatesRank)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesRank->quiz_id)->name;
        $action = "Cập nhật xếp loại cơ cấu đề thi ";
        parent::saveHistory($quizTemplatesRank,'Update',$action,$quizTemplate);
    }

    /**
     * Handle the quiz templates rank "deleted" event.
     *
     * @param  \App\QuizTemplatesRank  $quizTemplatesRank
     * @return void
     */
    public function deleted(QuizTemplatesRank $quizTemplatesRank)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesRank->quiz_id)->name;
        $action = "Xóa xếp loại cơ cấu đề thi ";
        parent::saveHistory($quizTemplatesRank,'Delete',$action,$quizTemplate);
    }

    /**
     * Handle the quiz templates rank "restored" event.
     *
     * @param  \App\QuizTemplatesRank  $quizTemplatesRank
     * @return void
     */
    public function restored(QuizTemplatesRank $quizTemplatesRank)
    {
        //
    }

    /**
     * Handle the quiz templates rank "force deleted" event.
     *
     * @param  \App\QuizTemplatesRank  $quizTemplatesRank
     * @return void
     */
    public function forceDeleted(QuizTemplatesRank $quizTemplatesRank)
    {
        //
    }
}
