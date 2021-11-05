<?php

namespace Modules\Promotion\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Promotion\Entities\PromotionUserHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int $point
 * @property string $type
 * @property int $source
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory whereUserId($value)
 * @mixin \Eloquent
 * @property int|null $course_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionUserHistory whereCourseId($value)
 */
class PromotionUserHistory extends Model
{
    protected $table = 'el_promotion_user_point_get_history';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'user_type',
        'point',
        'type',
        'course_id',
        'created_at',
        'updated_at',
        'promotion_course_setting_id',
        'daily_training',
        'video_id',
    ];

    public static function getHistoryPointCourseUser($user_id, $course_id, $course_type){
        $history = PromotionUserHistory::where('user_id', '=', $user_id)
        ->where('course_id', '=', $course_id)
        ->where('type', '=', $course_type)
        ->first();

        return $history ? $history->point : '';
    }
}
