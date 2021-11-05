<?php

namespace App\Observers;

use Modules\PlanApp\Entities\PlanAppTemplate;

class PlanAppTemplateObserver extends BaseObserver
{
    /**
     * Handle the plan app template "created" event.
     *
     * @param  \App\PlanAppTemplate  $planAppTemplate
     * @return void
     */
    public function created(PlanAppTemplate $planAppTemplate)
    {
        //
    }

    /**
     * Handle the plan app template "updated" event.
     *
     * @param  \App\PlanAppTemplate  $planAppTemplate
     * @return void
     */
    public function updated(PlanAppTemplate $planAppTemplate)
    {
        if ($planAppTemplate->isDirty(['name']))
            $this->updateHasChange($planAppTemplate,1);
    }

    /**
     * Handle the plan app template "deleted" event.
     *
     * @param  \App\PlanAppTemplate  $planAppTemplate
     * @return void
     */
    public function deleted(PlanAppTemplate $planAppTemplate)
    {
        $this->updateHasChange($planAppTemplate,1);
    }

    /**
     * Handle the plan app template "restored" event.
     *
     * @param  \App\PlanAppTemplate  $planAppTemplate
     * @return void
     */
    public function restored(PlanAppTemplate $planAppTemplate)
    {
        //
    }

    /**
     * Handle the plan app template "force deleted" event.
     *
     * @param  \App\PlanAppTemplate  $planAppTemplate
     * @return void
     */
    public function forceDeleted(PlanAppTemplate $planAppTemplate)
    {
        //
    }
}
