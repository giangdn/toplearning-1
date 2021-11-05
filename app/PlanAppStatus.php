<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PlanAppStatus
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlanAppStatus extends Model
{
    protected $table = 'el_plan_app_status';
    protected $fillable = [
        'id',
        'name',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Trạng thái'
        ];
    }

    public static function getStatus($id)
    {
        if ($id)
            return self::query()->where('id','=',$id)->value('name');
        return trans('app.planning');
    }
}
