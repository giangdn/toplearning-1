<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\ActivityScormScore
 *
 * @property int $id
 * @property int $user_id
 * @property int $activity_id
 * @property int $attempt_id
 * @property float|null $score_max
 * @property float|null $score_min
 * @property float $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereScoreMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereScoreMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereUserId($value)
 * @mixin \Eloquent
 * @property float|null $score_raw
 * @property string|null $status
 * @property-read \Modules\Online\Entities\ActivityScormAttempt|null $attempt
 * @property-read \Modules\Online\Entities\OnlineCourseActivity|null $course_activity
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereScoreRaw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormScore whereStatus($value)
 */
class ActivityScormScore extends Model
{
    protected $table = 'el_activity_scorm_scores';
    protected $fillable = [
        'user_id',
        'user_type',
        'activity_id',
        'attempt_id',
        'score_max',
        'score_min',
        'score_raw',
        'score',
        'status',
    ];

    public function course_activity() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourseActivity', 'id', 'activity_id');
    }

    public function attempt() {
        return $this->hasOne('Modules\Online\Entities\ActivityScormAttempt', 'id', 'attempt_id');
    }
}
