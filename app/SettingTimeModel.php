<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SettingTimeModel
 *
 * @property int $id
 * @property string $image
 * @property string|null $description
 * @property string $location
 * @property int $status
 * @property int $display_order
 * @property string|null $url
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeModel whereUrl($value)
 * @mixin \Eloquent
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereType($value)
 */
class SettingTimeModel extends Model
{
    protected $table = 'el_setting_time';
    protected $primaryKey = 'id';
    protected $fillable = [
        'start_time',
        'end_time',
        'session',
        'object',
        'value',
    ];
}
