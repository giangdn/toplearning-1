<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\ActivityScormUser
 *
 * @property int $id
 * @property int $activity_id
 * @property int $user_id
 * @property int $attempt_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormUser whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormUser whereAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormUser whereUserId($value)
 * @mixin \Eloquent
 */
class ActivityScormUser extends Model
{
    protected $table = 'el_activity_scorm_users';
    protected $fillable = [
        'user_id',
        'user_type',
        'activity_id',
        'attempt_id',
    ];
}
