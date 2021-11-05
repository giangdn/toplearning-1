<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LanguagesGroups
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LanguagesGroups newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguagesGroups newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguagesGroups query()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguagesGroups whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguagesGroups whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguagesGroups whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguagesGroups whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LanguagesGroups extends Model
{
    protected $table="el_languages_groups";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'slug',
    ];

    public static function getAttributeName() {
        return [
            'name' => 'Tên nhóm',
            'created_by' => 'Người tạo',
            'updated_by' => 'Người cập nhật',
        ];
    }
}
