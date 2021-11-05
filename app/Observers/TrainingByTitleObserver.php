<?php

namespace App\Observers;


use App\Models\Categories\Titles;
use Modules\TrainingByTitle\Entities\TrainingByTitle;

class TrainingByTitleObserver extends BaseObserver
{
    /**
     * Handle the training by title "created" event.
     *
     * @param  \App\TrainingByTitle  $trainingByTitle
     * @return void
     */
    public function created(TrainingByTitle $trainingByTitle)
    {
        $title = Titles::find($trainingByTitle->title_id)->name;
        $action = 'Thêm lộ trình đào tạo';
        parent::saveHistory($trainingByTitle,'Insert',$action,$title);
    }

    /**
     * Handle the training by title "updated" event.
     *
     * @param  \App\TrainingByTitle  $trainingByTitle
     * @return void
     */
    public function updated(TrainingByTitle $trainingByTitle)
    {
        $title = Titles::find($trainingByTitle->title_id)->name;
        $action = 'Cập nhật lộ trình đào tạo';
        parent::saveHistory($trainingByTitle,'Update',$action,$title);
    }

    /**
     * Handle the training by title "deleted" event.
     *
     * @param  \App\TrainingByTitle  $trainingByTitle
     * @return void
     */
    public function deleted(TrainingByTitle $trainingByTitle)
    {
        $title = Titles::find($trainingByTitle->title_id)->name;
        $action = 'Xóa lộ trình đào tạo';
        parent::saveHistory($trainingByTitle,'Delete',$action,$title);
    }

    /**
     * Handle the training by title "restored" event.
     *
     * @param  \App\TrainingByTitle  $trainingByTitle
     * @return void
     */
    public function restored(TrainingByTitle $trainingByTitle)
    {
        //
    }

    /**
     * Handle the training by title "force deleted" event.
     *
     * @param  \App\TrainingByTitle  $trainingByTitle
     * @return void
     */
    public function forceDeleted(TrainingByTitle $trainingByTitle)
    {
        //
    }
}
