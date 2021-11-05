<?php

namespace Modules\Role\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Role\Entities\RoleHasPermission
 *
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermission query()
 * @mixin \Eloquent
 * @property int $permission_id
 * @property int $role_id
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermission whereRoleId($value)
 */
class RoleHasPermission extends Model
{
    protected $table= 'el_role_has_permissions';
    public $timestamps = false;
    protected $fillable = [
        'permission_id',
        'role_id',
    ];
}
