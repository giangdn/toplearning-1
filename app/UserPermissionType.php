<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserPermissionType
 *
 * @property int $user_id
 * @property int $permission_id
 * @property int $permission_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermissionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermissionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermissionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermissionType wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermissionType wherePermissionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermissionType whereUserId($value)
 * @mixin \Eloquent
 */
class UserPermissionType extends Model
{
    protected $table = 'el_user_permission_type';
//    protected $primaryKey = ['user_id', 'stock_id'];
    public $timestamps = false;
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'permission_id',
        'permission_type_id',
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */


}
