<?php

namespace Modules\Offline\Entities;

use App\Profile;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineCourseComplete
 *
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseComplete newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseComplete newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseComplete query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseComplete whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseComplete whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseComplete whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseComplete whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseComplete whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Profile[] $users
 * @property-read int|null $users_count
 */
class OfflineCourseComplete extends Model
{
    protected $table = 'el_offline_course_complete';

    protected $fillable = [
        'course_id',
        'user_id',
    ];
    protected $primaryKey = 'id';

    public function users()
    {
        return $this->belongsToMany(Profile::class,'el_offline_course_complete','id','user_id');
    }
    public static function getAttributeName() {
        return [
            'course_id' => trans('lacourse.course_code'),
            'user_id' => 'user id',
        ];
    }

    public static function countCourseComplete($course_id)
    {
        return OfflineCourseComplete::query()->where('course_id','=',$course_id)->count();
    }

    public static function getUserCompleted($course_id)
    {
        return OfflineCourseComplete::with('users:id,user_id,email,firstname,lastname,gender')->where('course_id',$course_id)->get()->pluck('users')->flatten();
    }
}
