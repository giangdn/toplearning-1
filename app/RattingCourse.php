<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RattingCourse extends Model
{
    protected $table = 'el_ratting_course';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'program_content',
        'teacher',
        'organization',
        'quality_course',
        'type',
    ];

    public static function getAttributeName() {
        return [
            'program_content' => 'Nội dung',
            'teacher' => 'Giảng viên',
            'organization' => 'Tổ chức',
        ];
    }
}
