<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseSettingPercent
 *
 * @property int $id
 * @property int $course_id
 * @property int $course_activity_id
 * @property int|null $score
 * @property int|null $percent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseSettingPercent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseSettingPercent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseSettingPercent query()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseSettingPercent whereCourseActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseSettingPercent whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseSettingPercent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseSettingPercent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseSettingPercent wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseSettingPercent whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseSettingPercent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OnlineCourseSettingPercent extends Model
{
    protected $table = 'el_online_course_setting_percent';

    protected $fillable = [
        'course_id',
        'course_activity_id',
        'score',
        'percent'
    ];
    protected $primaryKey = 'id';

    public static function getScore($course_id, $user_id, $user_type = 1){
        $activities = OnlineCourseActivity::where('course_id', '=', $course_id)->get();

        $score = null;
        foreach ($activities as $activity) {
            $completed = $activity->checkComplete($user_id, $user_type);
            if ($completed) {
                $setting_percent = OnlineCourseSettingPercent::query()
                    ->where('course_id', '=', $course_id)
                    ->where('course_activity_id', '=', $activity->id)
                    ->first();

                if ($setting_percent){
                    $score += (($setting_percent->score ? $setting_percent->score : 1) * $setting_percent->percent)/100;
                }
            }
        }

        return $score;
    }
}
