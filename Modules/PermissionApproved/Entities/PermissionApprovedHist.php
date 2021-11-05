<?php

namespace Modules\PermissionApproved\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\PermissionApprovedHist
 *
 * @property int $id
 * @property int $unit_id
 * @property string $model_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedHist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedHist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedHist query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedHist whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedHist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedHist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedHist whereModelApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionApprovedHist whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PermissionApprovedHist extends Model
{
    protected $table = 'el_permission_approved_hist';
    protected $fillable = [
        'unit_id',
        'model_approved',
    ];
}
