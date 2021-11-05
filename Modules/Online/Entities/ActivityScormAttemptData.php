<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\ActivityScormAttemptData
 *
 * @property int $id
 * @property int $attempt_id
 * @property string $var_name
 * @property string $var_value
 * @property-read \Modules\Online\Entities\ActivityScormAttempt|null $attempt
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttemptData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttemptData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttemptData query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttemptData whereAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttemptData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttemptData whereVarName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityScormAttemptData whereVarValue($value)
 * @mixin \Eloquent
 */
class ActivityScormAttemptData extends Model
{
    public $timestamps = false;
    
    protected $table = 'el_activity_scorm_attempt_data';
    protected $fillable = [
        'attempt_id',
        'var_name',
        'var_value',
    ];
    
    public function attempt() {
        return $this->hasOne('Modules\Online\Entities\ActivityScormAttempt', 'id', 'attempt_id');
    }
}
