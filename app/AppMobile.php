<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Slider
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereUrl($value)
 * @mixin \Eloquent
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereType($value)
 * @property string $link đường dẫn đến trang download app
 * @method static \Illuminate\Database\Eloquent\Builder|AppMobile whereLink($value)
 */
class AppMobile extends Model
{
    protected $table = 'el_app_mobile';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'link',
        'type',
        'created_by',
        'updated_by',
    ];

    public static function getAttributeName() {
        return [
            'image' => 'Hình ảnh',
        ];
    }
}
