<?php

namespace App\Observers;


use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\QuestionCategoryUser;

class QuestionCategoryUserObserver extends BaseObserver
{
    /**
     * Handle the question category user "created" event.
     *
     * @param  \App\QuestionCategoryUser  $questionCategoryUser
     * @return void
     */
    public function created(QuestionCategoryUser $questionCategoryUser)
    {
        $cate = QuestionCategory::find($questionCategoryUser->category_id)->name;
        $action = "Thêm phân quyền ngân hàng câu hỏi";
        parent::saveHistory($questionCategoryUser,'Insert',$action,$cate);
    }

    /**
     * Handle the question category user "updated" event.
     *
     * @param  \App\QuestionCategoryUser  $questionCategoryUser
     * @return void
     */
    public function updated(QuestionCategoryUser $questionCategoryUser)
    {
        $cate = QuestionCategory::find($questionCategoryUser->category_id)->name;
        $action = "Cập nhật phân quyền ngân hàng câu hỏi";
        parent::saveHistory($questionCategoryUser,'Update',$action,$cate);
    }

    /**
     * Handle the question category user "deleted" event.
     *
     * @param  \App\QuestionCategoryUser  $questionCategoryUser
     * @return void
     */
    public function deleted(QuestionCategoryUser $questionCategoryUser)
    {
        $cate = QuestionCategory::find($questionCategoryUser->category_id)->name;
        $action = "Xóa phân quyền ngân hàng câu hỏi";
        parent::saveHistory($questionCategoryUser,'Delete',$action,$cate);
    }

    /**
     * Handle the question category user "restored" event.
     *
     * @param  \App\QuestionCategoryUser  $questionCategoryUser
     * @return void
     */
    public function restored(QuestionCategoryUser $questionCategoryUser)
    {
        //
    }

    /**
     * Handle the question category user "force deleted" event.
     *
     * @param  \App\QuestionCategoryUser  $questionCategoryUser
     * @return void
     */
    public function forceDeleted(QuestionCategoryUser $questionCategoryUser)
    {
        //
    }
}
