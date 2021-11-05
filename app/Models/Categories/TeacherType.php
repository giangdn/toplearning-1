<?php

namespace App\Models\Categories;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\TeacherType
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TeacherType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TeacherType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TeacherType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TeacherType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TeacherType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TeacherType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TeacherType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TeacherType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TeacherType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TeacherType extends BaseModel
{
    protected $table = 'el_teacher_type';
    protected $fillable = [
        'code',
        'name',
        'status'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã loại giảng viên',
            'name' => 'Tên loại giảng viên',
            'status' => 'Trạng thái'
        ];
    }
}
