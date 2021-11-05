<?php

namespace App\Observers;

use App\Models\Categories\TrainingPartner;

class TrainingPartnerObserver extends BaseObserver
{
    /**
     * Handle the training partner "created" event.
     *
     * @param  \App\TrainingParter  $trainingPartner
     * @return void
     */
    public function created(TrainingPartner $trainingPartner)
    {
        //
    }

    /**
     * Handle the training parter "updated" event.
     *
     * @param  \App\TrainingPartner  $trainingPartner
     * @return void
     */
    public function updated(TrainingPartner $trainingPartner)
    {
        if ($trainingPartner->isDirty(['code','name']))
            $this->updateHasChange($trainingPartner,1);
    }

    /**
     * Handle the training parter "deleted" event.
     *
     * @param  \App\TrainingPartner  $trainingPartner
     * @return void
     */
    public function deleted(TrainingPartner $trainingPartner)
    {
        $this->updateHasChange($trainingPartner,2);
    }

    /**
     * Handle the training parter "restored" event.
     *
     * @param  \App\TrainingPartner  $trainingPartner
     * @return void
     */
    public function restored(TrainingPartner $trainingPartner)
    {
        //
    }

    /**
     * Handle the training parter "force deleted" event.
     *
     * @param  \App\TrainingPartner  $trainingPartner
     * @return void
     */
    public function forceDeleted(TrainingPartner $trainingPartner)
    {
        //
    }
}
