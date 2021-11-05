<?php

namespace Modules\PermissionApproved\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\PermissionApprovedObjectTracking
 *
 * @property int $id
 * @property int $level
 * @property int $unit_id
 * @property int|null $unit_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $model_approved
 * @property int $object_id
 * @property int $permission_approved_id
 * @property int $permission_approved_hist_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking whereModelApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking whereObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking wherePermissionApprovedHistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking wherePermissionApprovedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObjectTracking whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class PermissionApprovedObjectTracking extends Model
{
    protected $table = 'el_permission_approved_object_tracking';
    protected $fillable = [
        'level',
        'unit_id',
        'unit_by',
        'model_approved',
        'created_by',
        'updated_by',
        'object_id',
        'permission_approved_id',
        'permission_approved_hist_id',
    ];
}
