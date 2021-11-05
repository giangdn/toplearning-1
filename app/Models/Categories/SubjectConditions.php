<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\SubjectConditions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\SubjectConditions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\SubjectConditions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\SubjectConditions query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $name_en
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\SubjectConditions whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\SubjectConditions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\SubjectConditions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\SubjectConditions whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\SubjectConditions whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\SubjectConditions whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\SubjectConditions whereUpdatedAt($value)
 */
class SubjectConditions extends Model
{
    protected $table = 'el_subject_conditions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'name_en',
        'status'
    ];

    public static function getAttributeName() {
        return [
            'code' => 'Mã điều kiện học phần',
            'name_en' => 'Tên điều kiện học phần (EN)',
            'name' => 'Tên điều kiện học phần',
            'status' => 'Trạng thái',
        ];
    }
}
