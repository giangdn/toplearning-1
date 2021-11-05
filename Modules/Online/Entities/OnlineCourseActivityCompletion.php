<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseActivityCompletion
 *
 * @property int $id
 * @property int $user_id
 * @property int $activity_id
 * @property int $course_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityCompletion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityCompletion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityCompletion query()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityCompletion whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityCompletion whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityCompletion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityCompletion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityCompletion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityCompletion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityCompletion whereUserId($value)
 * @mixin \Eloquent
 */
class OnlineCourseActivityCompletion extends Model
{
    protected $table = 'el_online_course_activity_completion';
    protected $fillable = [
        'user_id',
        'user_type',
        'activity_id',
        'course_id',
        'status',
    ];
}
