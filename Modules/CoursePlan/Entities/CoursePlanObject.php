<?php

namespace Modules\CoursePlan\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\CoursePlan\Entities\CoursePlanObject
 *
 * @property int $id
 * @property int $course_id
 * @property int $course_type
 * @property int|null $title_id
 * @property int|null $unit_id
 * @property int|null $unit_level
 * @property int $type
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereUnitLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanObject whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class CoursePlanObject extends Model
{
    protected $table = 'el_course_plan_object';
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
            'course_id' => 'Khóa học',
            'type' => 'Loại đối tượng',
        ];
    }
}
