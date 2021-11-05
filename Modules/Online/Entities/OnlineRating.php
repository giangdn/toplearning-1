<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineRating
 *
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property int $num_star
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRating query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRating whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRating whereNumStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRating whereUserId($value)
 * @mixin \Eloquent
 */
class OnlineRating extends Model
{
    protected $table = 'el_online_rating';
    protected $primaryKey = 'id';
    protected $fillable = [];

    public static function getRating($course_id, $user_id) {
        $user_type = getUserType();

        $query = self::query();
        return $query->where('course_id', '=', $course_id)
            ->where('user_id', '=', $user_id)
            ->where('user_type', '=', $user_type)
            ->first();
    }

    public static function getRatingValue($course_id,$value)
    {
        $rating = self::whereNumStar($value)->whereCourseId($course_id)->count();
        $total = OnlineCourse::find($course_id)->countRatingStar();
        $percent = $total > 0 ? round($rating/$total,1) * 100 : 0 ;
        return $percent;
    }
}
