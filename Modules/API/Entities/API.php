<?php

namespace Modules\API\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\API\Entities\API
 *
 * @property int $id
 * @property string $code
 * @property string $name tên api
 * @property string $url
 * @property string|null $param
 * @property int|null $error
 * @property string|null $start_time
 * @property string|null $end_time
 * @property int|null $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|API newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|API newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|API query()
 * @method static \Illuminate\Database\Eloquent\Builder|API whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|API whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|API whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|API whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|API whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|API whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|API whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|API whereParam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|API whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|API whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|API whereUrl($value)
 * @mixin \Eloquent
 */
class API extends Model
{
    protected $table = 'el_api';
    protected $fillable = [
        'code',
        'name',
        'url',
        'param',
        'error',
        'start_time',
        'end_time',
        'order',
    ];

}
