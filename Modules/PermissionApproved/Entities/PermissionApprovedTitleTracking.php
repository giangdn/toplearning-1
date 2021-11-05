<?php

namespace Modules\PermissionApproved\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\PermissionApprovedTitleTracking
 *
 * @property int $id
 * @property int $level
 * @property int $unit_id
 * @property int|null $unit_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $model_approved ten table
 * @property int $title_id
 * @property int $permission_approved_id
 * @property int $permission_approved_hist_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking whereModelApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking wherePermissionApprovedHistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking wherePermissionApprovedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitleTracking whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class PermissionApprovedTitleTracking extends Model
{
    protected $table = 'el_permission_approved_title_tracking';
    protected $fillable = [
        'level',
        'unit_id',
        'unit_by',
        'model_approved',
        'created_by',
        'updated_by',
        'title_id',
        'permission_approved_id',
        'permission_approved_hist_id',
    ];
}
