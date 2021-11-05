<?php

namespace App\Observers;


use App\ProfileView;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineResult;

class OnlineResultObserver extends BaseObserver
{
    /**
     * Handle the online result "created" event.
     *
     * @param  \App\OnlineResult  $onlineResult
     * @return void
     */
    public function created(OnlineResult $onlineResult)
    {
        $courseName = OnlineCourse::find($onlineResult->course_id)->name;
        $user = ProfileView::find($onlineResult->user_id)->full_name;
        $action = 'Thêm kết quả đào tạo '.$user.' (khóa học online)';
        parent::saveHistory($onlineResult,'Insert',$action,$courseName, $onlineResult->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online result "updated" event.
     *
     * @param  \App\OnlineResult  $onlineResult
     * @return void
     */
    public function updated(OnlineResult $onlineResult)
    {
        $courseName = OnlineCourse::find($onlineResult->course_id)->name;
        $user = ProfileView::find($onlineResult->user_id)->full_name;
        $action = 'Cập nhật kết quả đào tạo '.$user.' (khóa học online)';
        parent::saveHistory($onlineResult,'Update',$action,$courseName, $onlineResult->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online result "deleted" event.
     *
     * @param  \App\OnlineResult  $onlineResult
     * @return void
     */
    public function deleted(OnlineResult $onlineResult)
    {
        $courseName = OnlineCourse::find($onlineResult->course_id)->name;
        $user = ProfileView::find($onlineResult->user_id)->full_name;
        $action = 'Xóa kết quả đào tạo '.$user.' (khóa học online)';
        parent::saveHistory($onlineResult,'Delete',$action,$courseName, $onlineResult->course_id,app(OnlineCourse::class)->getTable());
    }

    /**
     * Handle the online result "restored" event.
     *
     * @param  \App\OnlineResult  $onlineResult
     * @return void
     */
    public function restored(OnlineResult $onlineResult)
    {
        //
    }

    /**
     * Handle the online result "force deleted" event.
     *
     * @param  \App\OnlineResult  $onlineResult
     * @return void
     */
    public function forceDeleted(OnlineResult $onlineResult)
    {
        //
    }
}
