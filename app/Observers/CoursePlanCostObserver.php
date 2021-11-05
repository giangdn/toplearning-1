<?php

namespace App\Observers;


use Modules\CoursePlan\Entities\CoursePlan;
use Modules\CoursePlan\Entities\CoursePlanCost;

class CoursePlanCostObserver extends BaseObserver
{
    /**
     * Handle the course plan cost "created" event.
     *
     * @param  \App\CoursePlanCost  $coursePlanCost
     * @return void
     */
    public function created(CoursePlanCost $coursePlanCost)
    {
        $coursePlanName = CoursePlan::find($coursePlanCost->course_id)->name;
        parent::saveHistory($coursePlanCost,'Insert','Thêm chi phí đào tạo (kế hoạch đào tạo tháng)',$coursePlanName, $coursePlanCost->course_id,app(CoursePlan::class)->getTable());
    }

    /**
     * Handle the course plan cost "updated" event.
     *
     * @param  \App\CoursePlanCost  $coursePlanCost
     * @return void
     */
    public function updated(CoursePlanCost $coursePlanCost)
    {
        $coursePlanName = CoursePlan::find($coursePlanCost->course_id)->name;
        parent::saveHistory($coursePlanCost,'Update','Sửa chi phí đào tạo (kế hoạch đào tạo tháng)',$coursePlanName,$coursePlanCost->course_id,app(CoursePlan::class)->getTable());
    }

    /**
     * Handle the course plan cost "deleted" event.
     *
     * @param  \App\CoursePlanCost  $coursePlanCost
     * @return void
     */
    public function deleted(CoursePlanCost $coursePlanCost)
    {
        $coursePlanName = CoursePlan::find($coursePlanCost->course_id)->name;
        parent::saveHistory($coursePlanCost,'Delete','Xóa đối tượng tham gia (kế hoạch đào tạo tháng)',$coursePlanName,$coursePlanCost->course_id,app(CoursePlan::class)->getTable());
    }

    /**
     * Handle the course plan cost "restored" event.
     *
     * @param  \App\CoursePlanCost  $coursePlanCost
     * @return void
     */
    public function restored(CoursePlanCost $coursePlanCost)
    {
        //
    }

    /**
     * Handle the course plan cost "force deleted" event.
     *
     * @param  \App\CoursePlanCost  $coursePlanCost
     * @return void
     */
    public function forceDeleted(CoursePlanCost $coursePlanCost)
    {
        //
    }
}
