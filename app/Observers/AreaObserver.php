<?php

namespace App\Observers;

use App\Models\Categories\Area;

class AreaObserver extends BaseObserver
{
    /**
     * Handle the area "created" event.
     *
     * @param  \App\Area  $area
     * @return void
     */
    public function created(Area $area)
    {
        //
    }

    /**
     * Handle the area "updated" event.
     *
     * @param  \App\Area  $area
     * @return void
     */
    public function updated(Area $area)
    {
        if ($area->isDirty(['code','name']))
            $this->updateHasChange($area,1);
    }

    /**
     * Handle the area "deleted" event.
     *
     * @param  \App\Area  $area
     * @return void
     */
    public function deleted(Area $area)
    {
        $this->updateHasChange($area,2);
    }

    /**
     * Handle the area "restored" event.
     *
     * @param  \App\Area  $area
     * @return void
     */
    public function restored(Area $area)
    {
        //
    }

    /**
     * Handle the area "force deleted" event.
     *
     * @param  \App\Area  $area
     * @return void
     */
    public function forceDeleted(Area $area)
    {
        //
    }
}
