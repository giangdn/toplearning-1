<?php

namespace App\Observers;


use App\ProfileView;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineCourse;

class IndemnifyObserver extends BaseObserver
{
    /**
     * Handle the indemnify "created" event.
     *
     * @param  \App\Indemnify  $indemnify
     * @return void
     */
    public function created(Indemnify $indemnify)
    {
        $courseName = OfflineCourse::find($indemnify->course_id)->name;
        parent::saveHistory($indemnify,'Insert','Thêm chi phí học viên (khóa học tập trung)',$courseName, $indemnify->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the indemnify "updated" event.
     *
     * @param  \App\Indemnify  $indemnify
     * @return void
     */
    public function updated(Indemnify $indemnify)
    {
        $courseName = OfflineCourse::find($indemnify->course_id)->name;
        $student = ProfileView::find($indemnify->user_id)->full_name;
        $action = 'Sửa chi phí học viên (khóa học tập trung)';
        if ($indemnify->isDirty('compensated') || $indemnify->isDirty('cost_indemnify') || $indemnify->isDirty('date_diff') || $indemnify->isDirty('contract'))
            $action = 'Cập nhật chi phí bồi hoàn học viên '.$student;
        parent::saveHistory($indemnify,'Update',$action,$courseName, $indemnify->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the indemnify "deleted" event.
     *
     * @param  \App\Indemnify  $indemnify
     * @return void
     */
    public function deleted(Indemnify $indemnify)
    {
        $courseName = OfflineCourse::find($indemnify->course_id)->name;
        parent::saveHistory($indemnify,'Delete','Xóa chi phí học viên (khóa học tập trung)',$courseName, $indemnify->course_id,app(OfflineCourse::class)->getTable());
    }

    /**
     * Handle the indemnify "restored" event.
     *
     * @param  \App\Indemnify  $indemnify
     * @return void
     */
    public function restored(Indemnify $indemnify)
    {
        //
    }

    /**
     * Handle the indemnify "force deleted" event.
     *
     * @param  \App\Indemnify  $indemnify
     * @return void
     */
    public function forceDeleted(Indemnify $indemnify)
    {
        //
    }
}
