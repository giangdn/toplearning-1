<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseActivityBbb
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityBbb newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityBbb newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityBbb query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityBbb whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityBbb whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityBbb whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityBbb whereUpdatedAt($value)
 */
class OnlineCourseActivityBbb extends Model
{
    protected $table = 'el_online_course_activity_bbbs';
    
}
