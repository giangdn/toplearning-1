<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\ActivityScormAttempt
 *
 * @property int $id
 * @property int $activity_id
 * @property int $user_id
 * @property int $attempt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Online\Entities\OnlineCourseActivityScorm|null $activity_scorm
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttempt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttempt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttempt query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttempt whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttempt whereAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttempt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttempt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttempt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttempt whereUserId($value)
 * @mixin \Eloquent
 */
class ActivityScormAttempt extends Model
{
    protected $table = 'el_activity_scorm_attempts';
    protected $fillable = [
        'activity_id',
        'user_id',
        'user_type',
        'attempt',
        'lesson_location',
        'suspend_data',
    ];

    public function activity_scorm() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourseActivityScorm', 'id', 'activity_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
