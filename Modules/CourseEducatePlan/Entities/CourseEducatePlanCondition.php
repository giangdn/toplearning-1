<?php

namespace Modules\CourseEducatePlan\Entities;

use Illuminate\Database\Eloquent\Model;

class CourseEducatePlanCondition extends Model
{
    protected $table = 'el_course_educate_plan_condition';
    protected $fillable = [
        'course_id',
        'ratio',
        'minscore',
        'survey',
        'certificate',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' => trans('lacourse.course') ,
            'ratio' => 'Tỉ lệ %',
            'minscore' => 'Điểm tối thiểu',
            'survey' => 'Thực hiện đánh giá',
            'certificate' => 'Chứng chỉ khóa học',

        ];
    }

    public static function checkExists($course_id)
    {
        $query = self::query();
        $query->where('course_id', '=', $course_id);
        return $query->exists();
    }
}
