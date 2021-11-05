<?php

namespace App\Observers;


use Modules\Quiz\Entities\QuestionCategory;

class QuestionCategoryObserver extends BaseObserver
{
    /**
     * Handle the question category "created" event.
     *
     * @param  \App\QuestionCategory  $questionCategory
     * @return void
     */
    public function created(QuestionCategory $questionCategory)
    {
        parent::saveHistory($questionCategory,'Insert','Thêm ngân hàng câu hỏi');
    }

    /**
     * Handle the question category "updated" event.
     *
     * @param  \App\QuestionCategory  $questionCategory
     * @return void
     */
    public function updated(QuestionCategory $questionCategory)
    {
        parent::saveHistory($questionCategory,'Update','Cập nhật ngân hàng câu hỏi');
    }

    /**
     * Handle the question category "deleted" event.
     *
     * @param  \App\QuestionCategory  $questionCategory
     * @return void
     */
    public function deleted(QuestionCategory $questionCategory)
    {
        parent::saveHistory($questionCategory,'Delete','Xóa ngân hàng câu hỏi');
    }

    /**
     * Handle the question category "restored" event.
     *
     * @param  \App\QuestionCategory  $questionCategory
     * @return void
     */
    public function restored(QuestionCategory $questionCategory)
    {
        //
    }

    /**
     * Handle the question category "force deleted" event.
     *
     * @param  \App\QuestionCategory  $questionCategory
     * @return void
     */
    public function forceDeleted(QuestionCategory $questionCategory)
    {
        //
    }
}
