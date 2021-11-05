<?php

namespace Modules\PermissionApproved\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\PermissionApprovedObject
 *
 * @property int $id
 * @property int $level
 * @property int $unit_id
 * @property int|null $unit_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $model_approved
 * @property int $object_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject whereModelApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject whereObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property int $permission_approved_id
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedObject wherePermissionApprovedId($value)
 */
class PermissionApprovedObject extends Model
{
    protected $table = 'el_permission_approved_object';
    protected $fillable = [
        'level',
        'unit_id',
        'unit_by',
        'model_approved',
        'created_by',
        'updated_by',
        'object_id',
        'permission_approved_id',
    ];
}
