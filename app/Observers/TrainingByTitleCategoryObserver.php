<?php

namespace App\Observers;


use App\Models\Categories\Titles;
use Modules\TrainingByTitle\Entities\TrainingByTitle;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;

class TrainingByTitleCategoryObserver extends BaseObserver
{
    /**
     * Handle the training by title category "created" event.
     *
     * @param  \App\TrainingByTitleCategory  $trainingByTitleCategory
     * @return void
     */
    public function created(TrainingByTitleCategory $trainingByTitleCategory)
    {
        $title = Titles::find($trainingByTitleCategory->title_id)->name;
        $action = 'Thêm danh mục '.$trainingByTitleCategory->name.' lộ trình đào tạo';
        parent::saveHistory($trainingByTitleCategory,'Insert',$action,$title,$trainingByTitleCategory->training_title_id,app(TrainingByTitle::class));
    }

    /**
     * Handle the training by title category "updated" event.
     *
     * @param  \App\TrainingByTitleCategory  $trainingByTitleCategory
     * @return void
     */
    public function updated(TrainingByTitleCategory $trainingByTitleCategory)
    {
        $title = Titles::find($trainingByTitleCategory->title_id)->name;
        $action = 'Cập nhật danh mục '.$trainingByTitleCategory->name.' lộ trình đào tạo';
        parent::saveHistory($trainingByTitleCategory,'Update',$action,$title,$trainingByTitleCategory->training_title_id,app(TrainingByTitle::class));
    }

    /**
     * Handle the training by title category "deleted" event.
     *
     * @param  \App\TrainingByTitleCategory  $trainingByTitleCategory
     * @return void
     */
    public function deleted(TrainingByTitleCategory $trainingByTitleCategory)
    {
        $title = Titles::find($trainingByTitleCategory->title_id)->name;
        $action = 'Xóa danh mục '.$trainingByTitleCategory->name.' lộ trình đào tạo';
        parent::saveHistory($trainingByTitleCategory,'Delete',$action,$title,$trainingByTitleCategory->training_title_id,app(TrainingByTitle::class));
    }

    /**
     * Handle the training by title category "restored" event.
     *
     * @param  \App\TrainingByTitleCategory  $trainingByTitleCategory
     * @return void
     */
    public function restored(TrainingByTitleCategory $trainingByTitleCategory)
    {
        //
    }

    /**
     * Handle the training by title category "force deleted" event.
     *
     * @param  \App\TrainingByTitleCategory  $trainingByTitleCategory
     * @return void
     */
    public function forceDeleted(TrainingByTitleCategory $trainingByTitleCategory)
    {
        //
    }
}
