<?php

namespace App\Observers;
use Modules\PermissionApproved\Entities\ApprovedProcess;

class ApprovedProcessObserver extends BaseObserver
{
    /**
     * Handle the approved process "created" event.
     *
     * @param  \App\ApprovedProcess  $approvedProcess
     * @return void
     */
    public function created(ApprovedProcess $approvedProcess)
    {
        $action = "Thêm quy trình phê duyệt";
        parent::saveHistory($approvedProcess,'Insert',$action,$approvedProcess->unit_name);
    }

    /**
     * Handle the approved process "updated" event.
     *
     * @param  \App\ApprovedProcess  $approvedProcess
     * @return void
     */
    public function updated(ApprovedProcess $approvedProcess)
    {
        $action = "Cập nhật quy trình phê duyệt";
        parent::saveHistory($approvedProcess,'Update',$action,$approvedProcess->unit_name);
    }

    /**
     * Handle the approved process "deleted" event.
     *
     * @param  \App\ApprovedProcess  $approvedProcess
     * @return void
     */
    public function deleted(ApprovedProcess $approvedProcess)
    {
        $action = "Xóa quy trình phê duyệt";
        parent::saveHistory($approvedProcess,'Delete',$action,$approvedProcess->unit_name);
    }

    /**
     * Handle the approved process "restored" event.
     *
     * @param  \App\ApprovedProcess  $approvedProcess
     * @return void
     */
    public function restored(ApprovedProcess $approvedProcess)
    {
        //
    }

    /**
     * Handle the approved process "force deleted" event.
     *
     * @param  \App\ApprovedProcess  $approvedProcess
     * @return void
     */
    public function forceDeleted(ApprovedProcess $approvedProcess)
    {
        //
    }
}
