<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseActivityHistory
 *
 * @property int $id
 * @property int $course_id
 * @property int $activity_id
 * @property int $course_activity_id
 * @property int $user_id
 * @property int $register_id
 * @property \Modules\Online\Entities\OnlineCourseActivity|null $course_activity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory whereCourseActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseActivityHistory whereUserId($value)
 * @mixin \Eloquent
 */
class OnlineCourseActivityHistory extends Model
{
    protected $table = 'el_online_course_activity_history';
    
    public function course_activity() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourseActivity', 'id', 'course_activity_id');
    }
}
