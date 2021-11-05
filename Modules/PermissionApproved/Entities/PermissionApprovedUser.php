<?php

namespace Modules\PermissionApproved\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\PermissionApprovedUser
 *
 * @property int $id
 * @property int $level
 * @property int $unit_id
 * @property int|null $unit_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $model_approved
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser whereModelApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser whereUserId($value)
 * @mixin \Eloquent
 * @property int $permission_approved_id
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUser wherePermissionApprovedId($value)
 */
class PermissionApprovedUser extends BaseModel
{
    protected $table = 'el_permission_approved_user';
    protected $fillable = [
        'level',
        'unit_id',
        'unit_by',
        'model_approved',
        'created_by',
        'updated_by',
        'user_id',
        'permission_approved_id',
    ];
}
