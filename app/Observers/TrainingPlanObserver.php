<?php

namespace App\Observers;


use Modules\TrainingPlan\Entities\TrainingPlan;

class TrainingPlanObserver extends BaseObserver
{
    public function retrieved(TrainingPlan $trainingPlan)
    {
    }
    public function created(TrainingPlan $trainingPlan)
    {
        parent::saveHistory($trainingPlan,'Insert','Thêm kế hoạch đào tạo năm');
    }

    /**
     * Handle the training plan "updated" event.
     *
     * @param  \App\TrainingPlan  $trainingPlan
     * @return void
     */
    public function updated(TrainingPlan $trainingPlan)
    {
        parent::saveHistory($trainingPlan,'Update','Sửa kế hoạch đào tạo năm');
    }

    /**
     * Handle the training plan "deleted" event.
     *
     * @param  \App\TrainingPlan  $trainingPlan
     * @return void
     */
    public function deleted(TrainingPlan $trainingPlan)
    {
        parent::saveHistory($trainingPlan,'Delete','Xóa kế hoạch đào tạo năm');
    }

    /**
     * Handle the training plan "restored" event.
     *
     * @param  \App\TrainingPlan  $trainingPlan
     * @return void
     */
    public function restored(TrainingPlan $trainingPlan)
    {
        //
    }

    /**
     * Handle the training plan "force deleted" event.
     *
     * @param  \App\TrainingPlan  $trainingPlan
     * @return void
     */
    public function forceDeleted(TrainingPlan $trainingPlan)
    {
        //
    }
}
