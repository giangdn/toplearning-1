<?php

namespace App\Observers;
use App\PermissionType;

class PermissionTypeObserver extends BaseObserver
{
    /**
     * Handle the permission type "created" event.
     *
     * @param  \App\PermissionType  $permissionType
     * @return void
     */
    public function created(PermissionType $permissionType)
    {
        $action = "Thêm nhóm quyền";
        parent::saveHistory($permissionType,'Insert',$action);
    }

    /**
     * Handle the permission type "updated" event.
     *
     * @param  \App\PermissionType  $permissionType
     * @return void
     */
    public function updated(PermissionType $permissionType)
    {
        $action = "Cập nhật nhóm quyền";
        parent::saveHistory($permissionType,'Update',$action);
    }

    /**
     * Handle the permission type "deleted" event.
     *
     * @param  \App\PermissionType  $permissionType
     * @return void
     */
    public function deleted(PermissionType $permissionType)
    {
        $action = "Xóa nhóm quyền";
        parent::saveHistory($permissionType,'Delete',$action);
    }

    /**
     * Handle the permission type "restored" event.
     *
     * @param  \App\PermissionType  $permissionType
     * @return void
     */
    public function restored(PermissionType $permissionType)
    {
        //
    }

    /**
     * Handle the permission type "force deleted" event.
     *
     * @param  \App\PermissionType  $permissionType
     * @return void
     */
    public function forceDeleted(PermissionType $permissionType)
    {
        //
    }
}
