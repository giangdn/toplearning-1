<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Parameter
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $type
 * @property float $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Parameter whereUpdatedAt($value)
 */
class Parameter extends Model
{
    protected $table = 'el_parameter';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'type',
        'score'
    ];
}
