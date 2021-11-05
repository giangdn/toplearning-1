<?php

namespace App\Observers;
use App\Permission;
use App\Role;
use Modules\Role\Entities\RoleHasPermission;

class RoleHasPermissionObserver extends BaseObserver
{
    /**
     * Handle the role has permission "created" event.
     *
     * @param  \App\RoleHasPermission  $roleHasPermission
     * @return void
     */
    public function created(RoleHasPermission $roleHasPermission)
    {
        $role = Role::find($roleHasPermission->role_id)->name;
        $permission = Permission::find($roleHasPermission->permission_id)->name;
        $action = "Thêm quyền ".$permission." vào vai trò";
        parent::saveHistory($roleHasPermission,'Insert',$action,$role,$roleHasPermission->role_id,app(Role::class)->getTable());
    }

    /**
     * Handle the role has permission "updated" event.
     *
     * @param  \App\RoleHasPermission  $roleHasPermission
     * @return void
     */
    public function updated(RoleHasPermission $roleHasPermission)
    {
        $role = Role::find($roleHasPermission->role_id)->name;
        $permission = Permission::find($roleHasPermission->permission_id)->name;
        $action = "Cập nhật quyền ".$permission." vào vai trò";
        parent::saveHistory($roleHasPermission,'Update',$action,$role,$roleHasPermission->role_id,app(Role::class)->getTable());
    }

    /**
     * Handle the role has permission "deleted" event.
     *
     * @param  \App\RoleHasPermission  $roleHasPermission
     * @return void
     */
    public function deleted(RoleHasPermission $roleHasPermission)
    {
        $role = Role::find($roleHasPermission->role_id)->name;
        $permission = Permission::find($roleHasPermission->permission_id)->name;
        $action = "Xóa quyền ".$permission." vào vai trò";
        parent::saveHistory($roleHasPermission,'Delete',$action,$role,$roleHasPermission->role_id,app(Role::class)->getTable());
    }

    /**
     * Handle the role has permission "restored" event.
     *
     * @param  \App\RoleHasPermission  $roleHasPermission
     * @return void
     */
    public function restored(RoleHasPermission $roleHasPermission)
    {
        //
    }

    /**
     * Handle the role has permission "force deleted" event.
     *
     * @param  \App\RoleHasPermission  $roleHasPermission
     * @return void
     */
    public function forceDeleted(RoleHasPermission $roleHasPermission)
    {
        //
    }
}
