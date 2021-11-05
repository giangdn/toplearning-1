<?php

namespace App\Observers;

use App\Permission;
use App\PermissionType;
use App\Role;
use App\RolePermissionType;

class RolePermissionTypeObserver extends BaseObserver
{
    /**
     * Handle the role permission type "created" event.
     *
     * @param  \App\RolePermissionType  $rolePermissionType
     * @return void
     */
    public function created(RolePermissionType $rolePermissionType)
    {
        $role = Role::find($rolePermissionType->role_id)->name;
        $permissionType = PermissionType::find($rolePermissionType->permission_type_id)->name;
        $permission = Permission::find($rolePermissionType->permission_id)->name;
        $action = "Thêm nhóm quyền ".$permissionType." quyền (".$permission.") vào vai trò";
        parent::saveHistory($rolePermissionType,'Insert',$action,$role,$rolePermissionType->role_id,app(Role::class)->getTable());
    }

    /**
     * Handle the role permission type "updated" event.
     *
     * @param  \App\RolePermissionType  $rolePermissionType
     * @return void
     */
    public function updated(RolePermissionType $rolePermissionType)
    {
        $role = Role::find($rolePermissionType->role_id)->name;
        $permissionType = PermissionType::find($rolePermissionType->permission_type_id)->name;
        $permission = Permission::find($rolePermissionType->permission_id)->name;
        $action = "Cập nhật nhóm quyền ".$permissionType." quyền (".$permission.") vào vai trò";
        parent::saveHistory($rolePermissionType,'Update',$action,$role,$rolePermissionType->role_id,app(Role::class)->getTable());
    }

    /**
     * Handle the role permission type "deleted" event.
     *
     * @param  \App\RolePermissionType  $rolePermissionType
     * @return void
     */
    public function deleted(RolePermissionType $rolePermissionType)
    {
        $role = Role::find($rolePermissionType->role_id)->name;
        $permissionType = PermissionType::find($rolePermissionType->permission_type_id)->name;
        $permission = Permission::find($rolePermissionType->permission_id)->name;
        $action = "Xóa nhóm quyền ".$permissionType." quyền (".$permission.") trong vai trò";
        parent::saveHistory($rolePermissionType,'Delete',$action,$role,$rolePermissionType->role_id,app(Role::class)->getTable());
    }

    /**
     * Handle the role permission type "restored" event.
     *
     * @param  \App\RolePermissionType  $rolePermissionType
     * @return void
     */
    public function restored(RolePermissionType $rolePermissionType)
    {
        //
    }

    /**
     * Handle the role permission type "force deleted" event.
     *
     * @param  \App\RolePermissionType  $rolePermissionType
     * @return void
     */
    public function forceDeleted(RolePermissionType $rolePermissionType)
    {
        //
    }
}
