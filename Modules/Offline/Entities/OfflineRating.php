<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineRating
 *
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property int $num_star
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRating query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRating whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRating whereNumStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRating whereUserId($value)
 * @mixin \Eloquent
 */
class OfflineRating extends Model
{
    protected $table = 'el_offline_rating';
    protected $primaryKey = 'id';
    protected $fillable = [];

    public static function getRating($course_id, $user_id) {
        $query = self::query();
        return $query->where('course_id', '=', $course_id)
            ->where('user_id', '=', $user_id)->first();
    }

    public static function getRatingValue($course_id,$value)
    {
        $rating = self::whereNumStar($value)->whereCourseId($course_id)->count();
        $total = OfflineCourse::find($course_id)->countRatingStar();
        $percent = $total > 0 ? round($rating/$total,1) * 100 : 0 ;
        return $percent;
    }
}
