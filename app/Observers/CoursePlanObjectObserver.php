<?php

namespace App\Observers;


use Modules\CoursePlan\Entities\CoursePlan;
use Modules\CoursePlan\Entities\CoursePlanObject;

class CoursePlanObjectObserver extends BaseObserver
{
    /**
     * Handle the course plan object "created" event.
     *
     * @param  \App\CoursePlanObject  $coursePlanObject
     * @return void
     */
    public function created(CoursePlanObject $coursePlanObject)
    {
        $coursePlanName = CoursePlan::find($coursePlanObject->course_id)->name;
        parent::saveHistory($coursePlanObject,'Insert','Thêm đối tượng tham gia (kế hoạch đào tạo tháng)',$coursePlanName, $coursePlanObject->course_id,app(CoursePlan::class)->getTable());
    }

    /**
     * Handle the course plan object "updated" event.
     *
     * @param  \App\CoursePlanObject  $coursePlanObject
     * @return void
     */
    public function updated(CoursePlanObject $coursePlanObject)
    {
        $coursePlanName = CoursePlan::find($coursePlanObject->course_id)->name;
        parent::saveHistory($coursePlanObject,'Update','Sửa đối tượng tham gia (kế hoạch đào tạo tháng)',$coursePlanName,$coursePlanObject->course_id,app(CoursePlan::class)->getTable());
    }

    /**
     * Handle the course plan object "deleted" event.
     *
     * @param  \App\CoursePlanObject  $coursePlanObject
     * @return void
     */
    public function deleted(CoursePlanObject $coursePlanObject)
    {
        $coursePlanName = CoursePlan::find($coursePlanObject->course_id)->name;
        parent::saveHistory($coursePlanObject,'Delete','Xóa đối tượng tham gia (kế hoạch đào tạo tháng)',$coursePlanName,$coursePlanObject->course_id,app(CoursePlan::class)->getTable());
    }

    /**
     * Handle the course plan object "restored" event.
     *
     * @param  \App\CoursePlanObject  $coursePlanObject
     * @return void
     */
    public function restored(CoursePlanObject $coursePlanObject)
    {
        //
    }

    /**
     * Handle the course plan object "force deleted" event.
     *
     * @param  \App\CoursePlanObject  $coursePlanObject
     * @return void
     */
    public function forceDeleted(CoursePlanObject $coursePlanObject)
    {
        //
    }
}
