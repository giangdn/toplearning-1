<?php

namespace App\Observers;


use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesQuestionCategory;

class QuizTemplatesQuestionCategoryObserver extends BaseObserver
{
    /**
     * Handle the quiz templates question category "created" event.
     *
     * @param  \App\QuizTemplatesQuestionCategory  $quizTemplatesQuestionCategory
     * @return void
     */
    public function created(QuizTemplatesQuestionCategory $quizTemplatesQuestionCategory)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesQuestionCategory->quiz_id)->name;
        $action = "Thêm đề mục câu hỏi cơ cấu đề thi ".$quizTemplate;
        parent::saveHistory($quizTemplatesQuestionCategory,'Insert',$action);
    }

    /**
     * Handle the quiz templates question category "updated" event.
     *
     * @param  \App\QuizTemplatesQuestionCategory  $quizTemplatesQuestionCategory
     * @return void
     */
    public function updated(QuizTemplatesQuestionCategory $quizTemplatesQuestionCategory)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesQuestionCategory->quiz_id)->name;
        $action = "Cập nhật đề mục câu hỏi cơ cấu đề thi ".$quizTemplate;
        parent::saveHistory($quizTemplatesQuestionCategory,'Update',$action);
    }

    /**
     * Handle the quiz templates question category "deleted" event.
     *
     * @param  \App\QuizTemplatesQuestionCategory  $quizTemplatesQuestionCategory
     * @return void
     */
    public function deleted(QuizTemplatesQuestionCategory $quizTemplatesQuestionCategory)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesQuestionCategory->quiz_id)->name;
        $action = "Xóa đề mục câu hỏi cơ cấu đề thi ".$quizTemplate;
        parent::saveHistory($quizTemplatesQuestionCategory,'Delete',$action);
    }

    /**
     * Handle the quiz templates question category "restored" event.
     *
     * @param  \App\QuizTemplatesQuestionCategory  $quizTemplatesQuestionCategory
     * @return void
     */
    public function restored(QuizTemplatesQuestionCategory $quizTemplatesQuestionCategory)
    {
        //
    }

    /**
     * Handle the quiz templates question category "force deleted" event.
     *
     * @param  \App\QuizTemplatesQuestionCategory  $quizTemplatesQuestionCategory
     * @return void
     */
    public function forceDeleted(QuizTemplatesQuestionCategory $quizTemplatesQuestionCategory)
    {
        //
    }
}
