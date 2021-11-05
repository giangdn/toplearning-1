<?php

namespace App\Observers;

use App\Models\Categories\Titles;

class TitlesObserver extends BaseObserver
{
    /**
     * Handle the titles "created" event.
     *
     * @param  \App\Titles  $titles
     * @return void
     */
    public function created(Titles $titles)
    {
        //
    }

    /**
     * Handle the titles "updated" event.
     *
     * @param  \App\Titles  $titles
     * @return void
     */
    public function updated(Titles $titles)
    {
        if ($titles->isDirty(['code','name']))
            $this->updateHasChange($titles,1);
    }

    /**
     * Handle the titles "deleted" event.
     *
     * @param  \App\Titles  $titles
     * @return void
     */
    public function deleted(Titles $titles)
    {
        $this->updateHasChange($titles,2);
    }

    /**
     * Handle the titles "restored" event.
     *
     * @param  \App\Titles  $titles
     * @return void
     */
    public function restored(Titles $titles)
    {
        //
    }

    /**
     * Handle the titles "force deleted" event.
     *
     * @param  \App\Titles  $titles
     * @return void
     */
    public function forceDeleted(Titles $titles)
    {
        //
    }
}
