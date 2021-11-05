<?php

namespace Modules\VirtualClassroom\Entities;

use Illuminate\Database\Eloquent\Model;

class VirtualClassroomTeacher extends Model
{
    protected $table = 'el_virtual_classroom_teacher';
    protected $fillable = [
        'virtual_classroom_id',
        'teacher_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'virtual_classroom_id' => 'Khóa học online',
            'teacher_id' => 'Giảng viên',
        ];
    }
}
