<?php

namespace App\Observers;


use App\ProfileView;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineInviteRegister;

class OnlineInviteRegisterObserver extends BaseObserver
{
    /**
     * Handle the online invite register "created" event.
     *
     * @param  \App\OnlineInviteRegister  $onlineInviteRegister
     * @return void
     */
    public function created(OnlineInviteRegister $onlineInviteRegister)
    {
        $courseName = OnlineCourse::find($onlineInviteRegister->course_id)->name;
        $student = ProfileView::find($onlineInviteRegister->user_id)->full_name;
        $action = 'Thêm mời ghi danh '.$student.' (khóa học online)';
        parent::saveHistory($onlineInviteRegister,'Insert',$action,$courseName, $onlineInviteRegister->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online invite register "updated" event.
     *
     * @param  \App\OnlineInviteRegister  $onlineInviteRegister
     * @return void
     */
    public function updated(OnlineInviteRegister $onlineInviteRegister)
    {
        $courseName = OnlineCourse::find($onlineInviteRegister->course_id)->name;
        $student = ProfileView::find($onlineInviteRegister->user_id)->full_name;
        $action = 'Cập nhật mời ghi danh '.$student.' (khóa học online)';
        parent::saveHistory($onlineInviteRegister,'Insert',$action,$courseName, $onlineInviteRegister->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online invite register "deleted" event.
     *
     * @param  \App\OnlineInviteRegister  $onlineInviteRegister
     * @return void
     */
    public function deleted(OnlineInviteRegister $onlineInviteRegister)
    {
        $courseName = OnlineCourse::find($onlineInviteRegister->course_id)->name;
        $student = ProfileView::find($onlineInviteRegister->user_id)->full_name;
        $action = 'Xóa mời ghi danh '.$student.' (khóa học online)';
        parent::saveHistory($onlineInviteRegister,'Insert',$action,$courseName, $onlineInviteRegister->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online invite register "restored" event.
     *
     * @param  \App\OnlineInviteRegister  $onlineInviteRegister
     * @return void
     */
    public function restored(OnlineInviteRegister $onlineInviteRegister)
    {
        //
    }

    /**
     * Handle the online invite register "force deleted" event.
     *
     * @param  \App\OnlineInviteRegister  $onlineInviteRegister
     * @return void
     */
    public function forceDeleted(OnlineInviteRegister $onlineInviteRegister)
    {
        //
    }
}
