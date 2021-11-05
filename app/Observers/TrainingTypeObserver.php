<?php

namespace App\Observers;

use App\Models\Categories\TrainingType;

class TrainingTypeObserver extends BaseObserver
{
    /**
     * Handle the training type "created" event.
     *
     * @param  \App\TrainingType  $trainingType
     * @return void
     */
    public function created(TrainingType $trainingType)
    {
        //
    }

    /**
     * Handle the training type "updated" event.
     *
     * @param  \App\TrainingType  $trainingType
     * @return void
     */
    public function updated(TrainingType $trainingType)
    {
        if ($trainingType->isDirty(['code','name']))
            $this->updateHasChange($trainingType,1);
    }

    /**
     * Handle the training type "deleted" event.
     *
     * @param  \App\TrainingType  $trainingType
     * @return void
     */
    public function deleted(TrainingType $trainingType)
    {
        $this->updateHasChange($trainingType,2);
    }

    /**
     * Handle the training type "restored" event.
     *
     * @param  \App\TrainingType  $trainingType
     * @return void
     */
    public function restored(TrainingType $trainingType)
    {
        //
    }

    /**
     * Handle the training type "force deleted" event.
     *
     * @param  \App\TrainingType  $trainingType
     * @return void
     */
    public function forceDeleted(TrainingType $trainingType)
    {
        //
    }
}
