<?php

namespace App\Observers;


use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesQuestion;

class QuizTemplatesQuestionObserver extends BaseObserver
{
    /**
     * Handle the quiz template question "created" event.
     *
     * @param  \App\QuizTemplateQuestion  $quizTemplateQuestion
     * @return void
     */
    public function created(QuizTemplatesQuestion $quizTemplatesQuestion)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesQuestion->quiz_id)->name;
        $action = "Thêm câu hỏi cơ cấu đề thi";
        parent::saveHistory($quizTemplatesQuestion,'Insert',$action,$quizTemplate);
    }

    /**
     * Handle the quiz template question "updated" event.
     *
     * @param  \App\QuizTemplateQuestion  $quizTemplateQuestion
     * @return void
     */
    public function updated(QuizTemplatesQuestion $quizTemplatesQuestion)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesQuestion->quiz_id)->name;
        $action = "Cập nhật câu hỏi cơ cấu đề thi";
        parent::saveHistory($quizTemplatesQuestion,'Update',$action,$quizTemplate);
    }

    /**
     * Handle the quiz template question "deleted" event.
     *
     * @param  \App\QuizTemplateQuestion  $quizTemplateQuestion
     * @return void
     */
    public function deleted(QuizTemplatesQuestion $quizTemplatesQuestion)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesQuestion->quiz_id)->name;
        $action = "Xóa câu hỏi cơ cấu đề thi";
        parent::saveHistory($quizTemplatesQuestion,'Delete',$action,$quizTemplate);
    }

    /**
     * Handle the quiz template question "restored" event.
     *
     * @param  \App\QuizTemplateQuestion  $quizTemplateQuestion
     * @return void
     */
    public function restored(QuizTemplatesQuestion $quizTemplatesQuestion)
    {
        //
    }

    /**
     * Handle the quiz template question "force deleted" event.
     *
     * @param  \App\QuizTemplateQuestion  $quizTemplateQuestion
     * @return void
     */
    public function forceDeleted(QuizTemplatesQuestion $quizTemplatesQuestion)
    {
        //
    }
}
