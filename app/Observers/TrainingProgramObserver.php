<?php

namespace App\Observers;

use App\Models\Categories\TrainingProgram;

class TrainingProgramObserver extends BaseObserver
{
    /**
     * Handle the training program "created" event.
     *
     * @param  \App\TrainingProgram  $trainingProgram
     * @return void
     */
    public function created(TrainingProgram $trainingProgram)
    {
        //
    }

    /**
     * Handle the training program "updated" event.
     *
     * @param  \App\TrainingProgram  $trainingProgram
     * @return void
     */
    public function updated(TrainingProgram $trainingProgram)
    {
        if ($trainingProgram->isDirty(['code','name'])){
            $this->updateHasChange($trainingProgram,1);
        }
    }

    /**
     * Handle the training program "deleted" event.
     *
     * @param  \App\TrainingProgram  $trainingProgram
     * @return void
     */
    public function deleted(TrainingProgram $trainingProgram)
    {
        $this->updateHasChange($trainingProgram,2);
    }

    /**
     * Handle the training program "restored" event.
     *
     * @param  \App\TrainingProgram  $trainingProgram
     * @return void
     */
    public function restored(TrainingProgram $trainingProgram)
    {
        //
    }

    /**
     * Handle the training program "force deleted" event.
     *
     * @param  \App\TrainingProgram  $trainingProgram
     * @return void
     */
    public function forceDeleted(TrainingProgram $trainingProgram)
    {
        //
    }
}
