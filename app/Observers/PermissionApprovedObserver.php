<?php

namespace App\Observers;

use Modules\PermissionApproved\Entities\ApprovedProcess;
use Modules\PermissionApproved\Entities\PermissionApproved;

class PermissionApprovedObserver extends  BaseObserver
{
    /**
     * Handle the permission approved "created" event.
     *
     * @param  \App\PermissionApproved  $permissionApproved
     * @return void
     */
    public function created(PermissionApproved $permissionApproved)
    {
        $approvedProcess = ApprovedProcess::where(['unit_id'=>$permissionApproved->unit_id])->value('unit_name');
        $level = $permissionApproved->level;
        $modelApproved = $permissionApproved->model_approved;
        $action = "Thêm cấp duyệt ".$level." ".$modelApproved;
        parent::saveHistory($permissionApproved,'Insert',$action,$approvedProcess);
    }

    /**
     * Handle the permission approved "updated" event.
     *
     * @param  \App\PermissionApproved  $permissionApproved
     * @return void
     */
    public function updated(PermissionApproved $permissionApproved)
    {
        $approvedProcess = ApprovedProcess::where(['unit_id'=>$permissionApproved->unit_id])->value('unit_name');
        $level = $permissionApproved->level;
        $modelApproved = $permissionApproved->model_approved;
        $action = "Cập nhật cấp duyệt ".$level." ".$modelApproved;
        parent::saveHistory($permissionApproved,'Update',$action,$approvedProcess);
    }

    /**
     * Handle the permission approved "deleted" event.
     *
     * @param  \App\PermissionApproved  $permissionApproved
     * @return void
     */
    public function deleted(PermissionApproved $permissionApproved)
    {
        $approvedProcess = ApprovedProcess::where(['unit_id'=>$permissionApproved->unit_id])->value('unit_name');
        $level = $permissionApproved->level;
        $modelApproved = $permissionApproved->model_approved;
        $action = "Xóa cấp duyệt ".$level." ".$modelApproved;
        parent::saveHistory($permissionApproved,'Delete',$action,$approvedProcess);
    }

    /**
     * Handle the permission approved "restored" event.
     *
     * @param  \App\PermissionApproved  $permissionApproved
     * @return void
     */
    public function restored(PermissionApproved $permissionApproved)
    {
        //
    }

    /**
     * Handle the permission approved "force deleted" event.
     *
     * @param  \App\PermissionApproved  $permissionApproved
     * @return void
     */
    public function forceDeleted(PermissionApproved $permissionApproved)
    {
        //
    }
}
