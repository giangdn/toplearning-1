<?php

namespace App\Observers;

use App\Models\Categories\TrainingLocation;

class TrainingLocationObserver extends BaseObserver
{
    /**
     * Handle the training location "created" event.
     *
     * @param  \App\TrainingLocation  $trainingLocation
     * @return void
     */
    public function created(TrainingLocation $trainingLocation)
    {

    }

    /**
     * Handle the training location "updated" event.
     *
     * @param  \App\TrainingLocation  $trainingLocation
     * @return void
     */
    public function updated(TrainingLocation $trainingLocation)
    {
        if ($trainingLocation->isDirty(['name'])){
            $this->updateHasChange($trainingLocation,1);
        }
    }

    /**
     * Handle the training location "deleted" event.
     *
     * @param  \App\TrainingLocation  $trainingLocation
     * @return void
     */
    public function deleted(TrainingLocation $trainingLocation)
    {
        $this->updateHasChange($trainingLocation,2);
    }

    /**
     * Handle the training location "restored" event.
     *
     * @param  \App\TrainingLocation  $trainingLocation
     * @return void
     */
    public function restored(TrainingLocation $trainingLocation)
    {
        //
    }

    /**
     * Handle the training location "force deleted" event.
     *
     * @param  \App\TrainingLocation  $trainingLocation
     * @return void
     */
    public function forceDeleted(TrainingLocation $trainingLocation)
    {
        //
    }
}
