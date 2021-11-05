<?php

namespace App\Observers;

use Modules\Rating\Entities\RatingTemplate;

class RatingTemplateObserver extends BaseObserver
{
    /**
     * Handle the rating template "created" event.
     *
     * @param  \App\RatingTemplate  $ratingTemplate
     * @return void
     */
    public function created(RatingTemplate $ratingTemplate)
    {
        //
    }

    /**
     * Handle the rating template "updated" event.
     *
     * @param  \App\RatingTemplate  $ratingTemplate
     * @return void
     */
    public function updated(RatingTemplate $ratingTemplate)
    {
        if ($ratingTemplate->isDirty(['name']))
            $this->updateHasChange($ratingTemplate,1);
    }

    /**
     * Handle the rating template "deleted" event.
     *
     * @param  \App\RatingTemplate  $ratingTemplate
     * @return void
     */
    public function deleted(RatingTemplate $ratingTemplate)
    {
        $this->updateHasChange($ratingTemplate,1);
    }

    /**
     * Handle the rating template "restored" event.
     *
     * @param  \App\RatingTemplate  $ratingTemplate
     * @return void
     */
    public function restored(RatingTemplate $ratingTemplate)
    {
        //
    }

    /**
     * Handle the rating template "force deleted" event.
     *
     * @param  \App\RatingTemplate  $ratingTemplate
     * @return void
     */
    public function forceDeleted(RatingTemplate $ratingTemplate)
    {
        //
    }
}
