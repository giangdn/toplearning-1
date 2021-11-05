<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LogoModel
 *
 * @property int $id
 * @property string $image
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Slider whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereType($value)
 */
class LogoModel extends BaseModel
{
    protected $table = 'el_logo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'status',
    ];

    public static function getAttributeName() {
        return [
            'image' => 'Hình ảnh',
        ];
    }
}
