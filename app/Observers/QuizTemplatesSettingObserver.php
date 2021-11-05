<?php

namespace App\Observers;

use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizTemplatesSetting;

class QuizTemplatesSettingObserver extends BaseObserver
{
    /**
     * Handle the quiz templates setting "created" event.
     *
     * @param  \App\QuizTemplatesSetting  $quizTemplatesSetting
     * @return void
     */
    public function created(QuizTemplatesSetting $quizTemplatesSetting)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesSetting->quiz_id)->name;
        $action = "Thêm thiết lập tùy chỉnh cơ cấu đề thi ";
        parent::saveHistory($quizTemplatesSetting,'Insert',$action,$quizTemplate);
    }

    /**
     * Handle the quiz templates setting "updated" event.
     *
     * @param  \App\QuizTemplatesSetting  $quizTemplatesSetting
     * @return void
     */
    public function updated(QuizTemplatesSetting $quizTemplatesSetting)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesSetting->quiz_id)->name;
        $action = "Cập nhât thiết lập tùy chỉnh cơ cấu đề thi ";
        parent::saveHistory($quizTemplatesSetting,'Update',$action,$quizTemplate);
    }

    /**
     * Handle the quiz templates setting "deleted" event.
     *
     * @param  \App\QuizTemplatesSetting  $quizTemplatesSetting
     * @return void
     */
    public function deleted(QuizTemplatesSetting $quizTemplatesSetting)
    {
        $quizTemplate = QuizTemplates::find($quizTemplatesSetting->quiz_id)->name;
        $action = "Xóa thiết lập tùy chỉnh cơ cấu đề thi ";
        parent::saveHistory($quizTemplatesSetting,'Delete',$action,$quizTemplate);
    }

    /**
     * Handle the quiz templates setting "restored" event.
     *
     * @param  \App\QuizTemplatesSetting  $quizTemplatesSetting
     * @return void
     */
    public function restored(QuizTemplatesSetting $quizTemplatesSetting)
    {
        //
    }

    /**
     * Handle the quiz templates setting "force deleted" event.
     *
     * @param  \App\QuizTemplatesSetting  $quizTemplatesSetting
     * @return void
     */
    public function forceDeleted(QuizTemplatesSetting $quizTemplatesSetting)
    {
        //
    }
}
