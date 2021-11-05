<?php

namespace Modules\Role\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Role\Entities\RoleHasPermissionType
 *
 * @property int $role_id
 * @property int $permission_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermissionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermissionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermissionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermissionType wherePermissionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleHasPermissionType whereRoleId($value)
 * @mixin \Eloquent
 */
class RoleHasPermissionType extends Model
{
    protected $table='el_role_has_permission_type';
    public $timestamps = false;
//    protected $primaryKey = ['role_id','permission_type_id'];
    protected $fillable = [
        'role_id',
        'permission_type_id',
    ];
}
