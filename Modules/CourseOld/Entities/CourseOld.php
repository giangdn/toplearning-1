<?php

namespace Modules\CourseOld\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\CourseOld\Entities\CourseOld
 *
 * @property int $id
 * @property string|null $course_code
 * @property string|null $course_name
 * @property string|null $user_code
 * @property string|null $full_name
 * @property string|null $unit
 * @property string|null $title
 * @property string|null $data
 * @property int|null $course_type
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $course_id
 * @property string|null $start_date
 * @property string|null $end_date
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereCourseCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereCourseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseOld whereUserCode($value)
 * @mixin \Eloquent
 */
class CourseOld extends BaseModel
{
    protected $table='el_course_old';
    protected $fillable = [
        'course_code',
        'course_name',
        'user_code',
        'full_name',
        'unit',
        'title',
        'course_type',
        'data',
        'course_id',
        'start_date',
        'end_date',
    ];
}
