<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SpeedText
 *
 * @property int $id
 * @property string $title
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SpeedText newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SpeedText newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SpeedText query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SpeedText whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SpeedText whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SpeedText whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SpeedText whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SpeedText whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SpeedText extends Model
{
    protected $table = 'el_speed_text';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'status',
    ];
}
