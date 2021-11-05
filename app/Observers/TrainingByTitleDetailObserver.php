<?php

namespace App\Observers;


use App\Models\Categories\Titles;
use Modules\TrainingByTitle\Entities\TrainingByTitle;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;

class TrainingByTitleDetailObserver extends BaseObserver
{
    /**
     * Handle the training by title detail "created" event.
     *
     * @param  \App\TrainingByTitleDetail  $trainingByTitleDetail
     * @return void
     */
    public function created(TrainingByTitleDetail $trainingByTitleDetail)
    {
        $title = Titles::find($trainingByTitleDetail->title_id)->name;
        $action = 'Thêm chuyên đề '.$trainingByTitleDetail->subject_name.' lộ trình đào tạo';
        parent::saveHistory($trainingByTitleDetail,'Insert',$action,$title,$trainingByTitleDetail->training_title_id,app(TrainingByTitle::class)->getTable());
    }

    /**
     * Handle the training by title detail "updated" event.
     *
     * @param  \App\TrainingByTitleDetail  $trainingByTitleDetail
     * @return void
     */
    public function updated(TrainingByTitleDetail $trainingByTitleDetail)
    {
        $title = Titles::find($trainingByTitleDetail->title_id)->name;
        $action = 'Cập nhật chuyên đề '.$trainingByTitleDetail->subject_name.' lộ trình đào tạo';
        parent::saveHistory($trainingByTitleDetail,'Update',$action,$title,$trainingByTitleDetail->training_title_id,app(TrainingByTitle::class)->getTable());
    }

    /**
     * Handle the training by title detail "deleted" event.
     *
     * @param  \App\TrainingByTitleDetail  $trainingByTitleDetail
     * @return void
     */
    public function deleted(TrainingByTitleDetail $trainingByTitleDetail)
    {
        $title = Titles::find($trainingByTitleDetail->title_id)->name;
        $action = 'Xóa chuyên đề '.$trainingByTitleDetail->subject_name.' lộ trình đào tạo';
        parent::saveHistory($trainingByTitleDetail,'Delete',$action,$title,$trainingByTitleDetail->training_title_id,app(TrainingByTitle::class)->getTable());
    }

    /**
     * Handle the training by title detail "restored" event.
     *
     * @param  \App\TrainingByTitleDetail  $trainingByTitleDetail
     * @return void
     */
    public function restored(TrainingByTitleDetail $trainingByTitleDetail)
    {
        //
    }

    /**
     * Handle the training by title detail "force deleted" event.
     *
     * @param  \App\TrainingByTitleDetail  $trainingByTitleDetail
     * @return void
     */
    public function forceDeleted(TrainingByTitleDetail $trainingByTitleDetail)
    {
        //
    }
}
