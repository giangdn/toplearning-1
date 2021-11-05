<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseCondition
 *
 * @property int $id
 * @property int $course_id
 * @property int $rating
 * @property int $orderby
 * @property string $activity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition whereActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition whereOrderby($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $grade_methor
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCondition whereGradeMethor($value)
 */
class OnlineCourseCondition extends Model
{
    protected $table = 'el_online_course_condition';
    protected $primaryKey = 'id';
    protected $fillable = ['rating', 'orderby', 'activity', 'grade_methor'];

    public static function getByCourse($course_id) {
        return OnlineCourseCondition::firstOrNew(['course_id' => $course_id]);
    }
}
