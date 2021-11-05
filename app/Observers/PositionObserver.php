<?php

namespace App\Observers;

use App\Models\Categories\Position;

class PositionObserver extends BaseObserver
{
    /**
     * Handle the position "created" event.
     *
     * @param  \App\Position  $position
     * @return void
     */
    public function created(Position $position)
    {
        //
    }

    /**
     * Handle the position "updated" event.
     *
     * @param  \App\Position  $position
     * @return void
     */
    public function updated(Position $position)
    {
        if ($position->isDirty(['code','name']))
            $this->updateHasChange($position,1);
    }

    /**
     * Handle the position "deleted" event.
     *
     * @param  \App\Position  $position
     * @return void
     */
    public function deleted(Position $position)
    {
        $this->updateHasChange($position,2);
    }

    /**
     * Handle the position "restored" event.
     *
     * @param  \App\Position  $position
     * @return void
     */
    public function restored(Position $position)
    {
        //
    }

    /**
     * Handle the position "force deleted" event.
     *
     * @param  \App\Position  $position
     * @return void
     */
    public function forceDeleted(Position $position)
    {
        //
    }
}
