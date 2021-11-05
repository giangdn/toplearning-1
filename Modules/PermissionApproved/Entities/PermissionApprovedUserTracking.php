<?php

namespace Modules\PermissionApproved\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\PermissionApprovedUserTracking
 *
 * @property int $id
 * @property int $level
 * @property int $unit_id
 * @property int|null $unit_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $model_approved
 * @property int $user_id
 * @property int $permission_approved_id
 * @property int $permission_approved_hist_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking whereModelApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking wherePermissionApprovedHistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking wherePermissionApprovedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedUserTracking whereUserId($value)
 * @mixin \Eloquent
 */
class PermissionApprovedUserTracking extends Model
{
    protected $table = 'el_permission_approved_user_tracking';
    protected $fillable = [
        'level',
        'unit_id',
        'unit_by',
        'model_approved',
        'created_by',
        'updated_by',
        'user_id',
        'permission_approved_id',
        'permission_approved_hist_id',
    ];
}
