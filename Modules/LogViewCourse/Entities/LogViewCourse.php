<?php

namespace Modules\LogViewCourse\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\LogViewCourse\Entities\LogViewCourse
 *
 * @property int $id
 * @property int $course_id
 * @property string $course_code
 * @property int $course_type
 * @property string $course_name
 * @property int $user_id
 * @property string $user_name
 * @property string $session_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $last_access
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse query()
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereCourseCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereCourseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereLastAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogViewCourse whereUserName($value)
 * @mixin \Eloquent
 */
class LogViewCourse extends Model
{
    protected $table= 'el_log_view_course';
    protected $fillable = [
        'course_id',
        'course_code',
        'course_type',
        'course_name',
        'user_id',
        'user_name',
        'session_id',
        'ip_address',
        'user_agent',
        'last_access',
    ];
}
