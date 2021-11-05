<?php

namespace Modules\Online\Entities;

use App\Profile;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseComplete
 *
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseComplete newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseComplete newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseComplete query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseComplete whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseComplete whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseComplete whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseComplete whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseComplete whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Profile[] $users
 * @property-read int|null $users_count
 */
class OnlineCourseComplete extends Model
{
    protected $table = 'el_online_course_complete';

    protected $fillable = [
        'course_id',
        'user_id',
        'user_type',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' => trans('lacourse.course_code'),
            'user_id' => 'user id',
        ];
    }
    public function users()
    {
        return $this->belongsToMany(Profile::class,'el_online_course_complete','id','user_id');
    }
    public static function getUserCompleted($course_id)
    {
        return OnlineCourseComplete::with('users:id,user_id,email,firstname,lastname,gender')->where('course_id',$course_id)->get()->pluck('users')->flatten();
    }
}
