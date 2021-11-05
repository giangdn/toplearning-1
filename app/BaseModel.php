<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $user_id = \Auth::id() ?? 0;
            $model->created_by = $user_id;
            $model->updated_by = $user_id;
            $model->unit_by = session('user_unit')?? Profile::getUnitId();
        });
        static::updating(function($model)
        {
            $user_id = \Auth::id() ?? 0;
            $model->updated_by = $user_id;
        });
    }
}
