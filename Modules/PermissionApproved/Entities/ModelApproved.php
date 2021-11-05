<?php

namespace Modules\PermissionApproved\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\PermissionApproved\Entities\ModelApproved
 *
 * @property string $model
 * @property string $name
 * @property int $status 1: active, 0: unactive
 * @method static \Illuminate\Database\Eloquent\Builder|ModelApproved newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelApproved newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelApproved query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelApproved whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelApproved whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelApproved whereStatus($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ModelApproved active()
 */
class ModelApproved extends Model
{
    protected $table = 'el_model_approved';
    public $timestamps = false;
    protected $fillable = [
        'model',
        'name',
        'status',
    ];
    public function scopeActive($query) {
        return $query->where('status', '=', 1);
    }
}
