<?php

namespace Modules\PermissionApproved\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\PermissionApproved
 *
 * @property int $id
 * @property int|null $level
 * @property int $company_id
 * @property int|null $unit_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $model_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereModelApprovedCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property int|null $has_change
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereHasChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereModelApproved($value)
 * @property int $unit_id
 * @property string|null $hierarchy
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereHierarchy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApproved whereUnitId($value)
 */
class PermissionApproved extends BaseModel
{
    protected $table = 'el_permission_approved';
    protected $fillable = [
        'level',
        'unit_id',
        'unit_by',
        'created_by',
        'updated_by',
        'model_approved',
        'has_change',
    ];
}
