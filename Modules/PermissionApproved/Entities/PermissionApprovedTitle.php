<?php

namespace Modules\PermissionApproved\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\PermissionApprovedTitle
 *
 * @property int $id
 * @property int $level
 * @property int $unit_id
 * @property int|null $unit_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $model_approved
 * @property int $title_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle whereModelApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property int $permission_approved_id
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedTitle wherePermissionApprovedId($value)
 */
class PermissionApprovedTitle extends BaseModel
{
    protected $table = 'el_permission_approved_title';
    protected $fillable = [
        'level',
        'unit_id',
        'unit_by',
        'model_approved',
        'created_by',
        'updated_by',
        'title_id',
        'permission_approved_id',
    ];
}
