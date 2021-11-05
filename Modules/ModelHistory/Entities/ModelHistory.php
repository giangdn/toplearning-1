<?php

namespace Modules\ModelHistory\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModelHistory\Entities\ModelHistory
 *
 * @property int $id
 * @property int $model_id
 * @property string $model
 * @property string|null $action
 * @property string|null $note
 * @property int|null $parent_id
 * @property string|null $parent_model
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereParentModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereName($value)
 * @property int|null $unit_by
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereUnitBy($value)
 * @property string|null $created_name
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereCreatedName($value)
 * @property string|null $code
 * @method static \Illuminate\Database\Eloquent\Builder|ModelHistory whereCode($value)
 */
class ModelHistory extends BaseModel
{
    protected $table='el_model_history';
    protected $fillable = [
        'model_id',
        'model',
        'code',
        'action',
        'note',
        'parent_id',
        'parent_model',
        'unit_by',
        'created_name',
    ];
}
