<?php

namespace App\Observers;


use Modules\CoursePlan\Entities\CoursePlan;
use Modules\ModelHistory\Entities\ModelHistory;

class CoursePlanObserver extends BaseObserver
{
    /**
     * Handle the course plan "created" event.
     *
     * @param  \App\CoursePlan  $coursePlan
     * @return void
     */
    public function created(CoursePlan $coursePlan)
    {
        parent::saveHistory($coursePlan,'Insert','Thêm kế hoạch đào tạo tháng');
    }

    /**
     * Handle the course plan "updated" event.
     *
     * @param  \App\CoursePlan  $coursePlan
     * @return void
     */
    public function updated(CoursePlan $coursePlan)
    {
        $action = $coursePlan->isDirty('approved_step')?'Phê duyệt kế hoạch đào tạo tháng':'Sửa kế hoạch đào tạo tháng';
        parent::saveHistory($coursePlan,'Update',$action);
    }

    /**
     * Handle the course plan "deleted" event.
     *
     * @param  \App\CoursePlan  $coursePlan
     * @return void
     */
    public function deleted(CoursePlan $coursePlan)
    {
        parent::saveHistory($coursePlan,'Delete','Xóa kế hoạch đào tạo tháng');
    }

    /**
     * Handle the course plan "restored" event.
     *
     * @param  \App\CoursePlan  $coursePlan
     * @return void
     */
    public function restored(CoursePlan $coursePlan)
    {
        //
    }

    /**
     * Handle the course plan "force deleted" event.
     *
     * @param  \App\CoursePlan  $coursePlan
     * @return void
     */
    public function forceDeleted(CoursePlan $coursePlan)
    {

    }
}
