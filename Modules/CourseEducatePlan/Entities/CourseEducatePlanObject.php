<?php

namespace Modules\CourseEducatePlan\Entities;

use Illuminate\Database\Eloquent\Model;

class CourseEducatePlanObject extends Model
{
    protected $table = 'el_course_educate_plan_object';
    protected $fillable = [
        'course_id',
        'course_type',
        'title_id',
        'unit_id',
        'unit_level',
        'type',
        'created_by',
        'updated_by'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' => trans('lacourse.course'),
            'type' => 'Loại đối tượng',
        ];
    }
}
