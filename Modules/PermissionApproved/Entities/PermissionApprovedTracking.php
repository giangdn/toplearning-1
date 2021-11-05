<?php

namespace Modules\PermissionApproved\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\PermissionApprovedTracking
 *
 * @property int $id
 * @property int|null $level
 * @property int $unit_id
 * @property int|null $unit_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $model_approved
 * @property int $permission_approved_hist_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking whereModelApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking wherePermissionApprovedHistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property int|null $permission_approved_id
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTracking wherePermissionApprovedId($value)
 */
class PermissionApprovedTracking extends Model
{
    protected $table = 'el_permission_approved_tracking';
    protected $fillable = [
        'level',
        'unit_id',
        'unit_by',
        'created_by',
        'updated_by',
        'model_approved',
        'permission_approved_id',
        'permission_approved_hist_id',
    ];
}
