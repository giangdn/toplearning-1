<?php

namespace App\Observers;
use App\ProfileView;
use App\Role;
use App\UserRole;

class UserRoleObserver extends BaseObserver
{
    /**
     * Handle the user role "created" event.
     *
     * @param  \App\UserRole  $userRole
     * @return void
     */
    public function created(UserRole $userRole)
    {
        $role = Role::find($userRole->role_id)->name;
        $user = ProfileView::find($userRole->user_id)->full_name;
        $action = "Thêm user ".$user." vào vai trò";
        parent::saveHistory($userRole,'Insert',$action,$role,$userRole->role_id,app(Role::class)->getTable());
    }

    /**
     * Handle the user role "updated" event.
     *
     * @param  \App\UserRole  $userRole
     * @return void
     */
    public function updated(UserRole $userRole)
    {
        $role = Role::find($userRole->role_id)->name;
        $user = ProfileView::find($userRole->user_id)->full_name;
        $action = "Cập nhật user ".$user." trong vai trò";
        parent::saveHistory($userRole,'Update',$action,$role,$userRole->role_id,app(Role::class)->getTable());
    }

    /**
     * Handle the user role "deleted" event.
     *
     * @param  \App\UserRole  $userRole
     * @return void
     */
    public function deleted(UserRole $userRole)
    {
        $role = Role::find($userRole->role_id)->name;
        $user = ProfileView::find($userRole->user_id)->full_name;
        $action = "Xóa user ".$user." trong vai trò";
        parent::saveHistory($userRole,'Delete',$action,$role,$userRole->role_id,app(Role::class)->getTable());
    }

    /**
     * Handle the user role "restored" event.
     *
     * @param  \App\UserRole  $userRole
     * @return void
     */
    public function restored(UserRole $userRole)
    {
        //
    }

    /**
     * Handle the user role "force deleted" event.
     *
     * @param  \App\UserRole  $userRole
     * @return void
     */
    public function forceDeleted(UserRole $userRole)
    {
        //
    }
}
