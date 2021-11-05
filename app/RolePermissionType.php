<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RolePermissionType
 *
 * @property int $role_id
 * @property int $permission_id
 * @property int $permission_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermissionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermissionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermissionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermissionType wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermissionType wherePermissionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermissionType whereRoleId($value)
 * @mixin \Eloquent
 */
class RolePermissionType extends Model
{
    protected $table = 'el_role_permission_type';
    protected $primaryKey = ['user_id', 'stock_id'];
    public $timestamps = false;
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'permission_id',
        'permission_type_id',
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */


}
