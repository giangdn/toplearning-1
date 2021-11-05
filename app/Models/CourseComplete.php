<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseComplete
 *
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property int|null $course_type 1: online, 2: offline
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CourseComplete newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseComplete newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseComplete query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseComplete whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseComplete whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseComplete whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseComplete whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseComplete whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseComplete whereUserId($value)
 * @mixin \Eloquent
 */
class CourseComplete extends Model
{
    protected $table='el_course_complete';
    protected $fillable = [
        'course_id',
        'user_id',
        'user_type',
        'course_type',
    ];
}
