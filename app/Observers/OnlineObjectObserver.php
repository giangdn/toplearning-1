<?php

namespace App\Observers;


use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineObject;

class OnlineObjectObserver extends BaseObserver
{
    /**
     * Handle the online object "created" event.
     *
     * @param  \App\OnlineObject  $onlineObject
     * @return void
     */
    public function created(OnlineObject $onlineObject)
    {
        $courseName = OnlineCourse::find($onlineObject->course_id)->name;
        parent::saveHistory($onlineObject,'Insert','Thêm đối tượng tham gia (khóa học online)',$courseName, $onlineObject->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online object "updated" event.
     *
     * @param  \App\OnlineObject  $onlineObject
     * @return void
     */
    public function updated(OnlineObject $onlineObject)
    {
        $courseName = OnlineCourse::find($onlineObject->course_id)->name;
        parent::saveHistory($onlineObject,'Update','Cập nhật đối tượng tham gia (khóa học online)',$courseName, $onlineObject->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online object "deleted" event.
     *
     * @param  \App\OnlineObject  $onlineObject
     * @return void
     */
    public function deleted(OnlineObject $onlineObject)
    {
        $courseName = OnlineCourse::find($onlineObject->course_id)->name;
        parent::saveHistory($onlineObject,'Delete','Xóa đối tượng tham gia (khóa học online)',$courseName, $onlineObject->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online object "restored" event.
     *
     * @param  \App\OnlineObject  $onlineObject
     * @return void
     */
    public function restored(OnlineObject $onlineObject)
    {
        //
    }

    /**
     * Handle the online object "force deleted" event.
     *
     * @param  \App\OnlineObject  $onlineObject
     * @return void
     */
    public function forceDeleted(OnlineObject $onlineObject)
    {
        //
    }
}
