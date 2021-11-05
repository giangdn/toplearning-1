<?php

namespace App\Observers;

use App\Models\Categories\TrainingForm;

class TrainingFormObserver extends BaseObserver
{
    /**
     * Handle the training form "created" event.
     *
     * @param  \App\TrainingForm  $trainingForm
     * @return void
     */
    public function created(TrainingForm $trainingForm)
    {
        //
    }

    /**
     * Handle the training form "updated" event.
     *
     * @param  \App\TrainingForm  $trainingForm
     * @return void
     */
    public function updated(TrainingForm $trainingForm)
    {
        if ($trainingForm->isDirty(['code','name']))
            $this->updateHasChange($trainingForm,1);
    }

    /**
     * Handle the training form "deleted" event.
     *
     * @param  \App\TrainingForm  $trainingForm
     * @return void
     */
    public function deleted(TrainingForm $trainingForm)
    {
        $this->updateHasChange($trainingForm,2);
    }

    /**
     * Handle the training form "restored" event.
     *
     * @param  \App\TrainingForm  $trainingForm
     * @return void
     */
    public function restored(TrainingForm $trainingForm)
    {
        //
    }

    /**
     * Handle the training form "force deleted" event.
     *
     * @param  \App\TrainingForm  $trainingForm
     * @return void
     */
    public function forceDeleted(TrainingForm $trainingForm)
    {
        //
    }
}
