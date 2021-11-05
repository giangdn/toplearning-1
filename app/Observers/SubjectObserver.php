<?php

namespace App\Observers;

use App\HasChange;
use App\Models\Categories\Subject;

class SubjectObserver extends BaseObserver
{
    /**
     * Handle the subject "created" event.
     *
     * @param  \App\Subject  $subject
     * @return void
     */
    public function created(Subject $subject)
    {
        //
    }

    /**
     * Handle the subject "updated" event.
     *
     * @param  \App\Subject  $subject
     * @return void
     */
    public function updated(Subject $subject)
    {
        if ($subject->isDirty(['code','name'])){
            $this->updateHasChange($subject,1);
        }
    }

    /**
     * Handle the subject "deleted" event.
     *
     * @param  \App\Subject  $subject
     * @return void
     */
    public function deleted(Subject $subject)
    {
        $this->updateHasChange($subject,2);
    }

    /**
     * Handle the subject "restored" event.
     *
     * @param  \App\Subject  $subject
     * @return void
     */
    public function restored(Subject $subject)
    {
        //
    }

    /**
     * Handle the subject "force deleted" event.
     *
     * @param  \App\Subject  $subject
     * @return void
     */
    public function forceDeleted(Subject $subject)
    {
        //
    }
}
