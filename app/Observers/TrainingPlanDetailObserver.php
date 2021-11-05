<?php

namespace App\Observers;


use Modules\TrainingPlan\Entities\TrainingPlan;
use Modules\TrainingPlan\Entities\TrainingPlanDetail;

class TrainingPlanDetailObserver extends BaseObserver
{
    /**
     * Handle the training plan detail "created" event.
     *
     * @param  \App\TrainingPlanDetail  $trainingPlanDetail
     * @return void
     */
    public function created(TrainingPlanDetail $trainingPlanDetail)
    {
        $trainingPlanName = TrainingPlan::find($trainingPlanDetail->plan_id)->name;
        parent::saveHistory($trainingPlanDetail,'Insert','Thêm chi tiết kế hoạch đào tạo (kế hoạch đào tạo năm)',$trainingPlanName, $trainingPlanDetail->plan_id,app(TrainingPlan::class)->getTable());
    }

    /**
     * Handle the training plan detail "updated" event.
     *
     * @param  \App\TrainingPlanDetail  $trainingPlanDetail
     * @return void
     */
    public function updated(TrainingPlanDetail $trainingPlanDetail)
    {
        $trainingPlanName = TrainingPlan::find($trainingPlanDetail->plan_id)->name;
        parent::saveHistory($trainingPlanDetail,'Update','Sửa chi tiết kế hoạch đào tạo (kế hoạch đào tạo năm)',$trainingPlanName, $trainingPlanDetail->plan_id,app(TrainingPlan::class)->getTable());
    }

    /**
     * Handle the training plan detail "deleted" event.
     *
     * @param  \App\TrainingPlanDetail  $trainingPlanDetail
     * @return void
     */
    public function deleted(TrainingPlanDetail $trainingPlanDetail)
    {
        $trainingPlanName = TrainingPlan::find($trainingPlanDetail->plan_id)->name;
        parent::saveHistory($trainingPlanDetail,'Delete','Xóa chi tiết kế hoạch đào tạo (kế hoạch đào tạo năm)',$trainingPlanName, $trainingPlanDetail->plan_id,app(TrainingPlan::class)->getTable());
    }

    /**
     * Handle the training plan detail "restored" event.
     *
     * @param  \App\TrainingPlanDetail  $trainingPlanDetail
     * @return void
     */
    public function restored(TrainingPlanDetail $trainingPlanDetail)
    {
        //
    }

    /**
     * Handle the training plan detail "force deleted" event.
     *
     * @param  \App\TrainingPlanDetail  $trainingPlanDetail
     * @return void
     */
    public function forceDeleted(TrainingPlanDetail $trainingPlanDetail)
    {
        //
    }
}
