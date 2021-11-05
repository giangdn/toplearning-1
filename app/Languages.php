<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Languages
 *
 * @property int $id
 * @property string $pkey
 * @property string $content
 * @property string $content_en
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Languages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Languages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Languages query()
 * @method static \Illuminate\Database\Eloquent\Builder|Languages whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Languages whereContentEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Languages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Languages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Languages wherePkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Languages whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Languages extends Model
{
    protected $table="el_languages";
    protected $primaryKey = 'id';
    protected $fillable = [
        'pkey',
        'content',
        'content_en',
        'created_by',
        'updated_by',
    ];

    public static function getAttributeName() {
        return [
            'pkey' => 'Từ khóa',
            'content' => 'Nội dung Tiếng Việt',
            'content_en' => 'Nội dung Tiếng Anh',
            'created_by' => 'Người tạo',
            'updated_by' => 'Người cập nhật',
        ];
    }
}
