<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SettingTimeObjectModel
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SettingTimeObjectModel whereUrl($value)
 * @mixin \Eloquent
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeObjectModel whereType($value)
 */
class SettingTimeObjectModel extends Model
{
    protected $table = 'el_setting_time_object';
    protected $primaryKey = 'id';
    protected $fillable = [
        'object',
    ];
}
