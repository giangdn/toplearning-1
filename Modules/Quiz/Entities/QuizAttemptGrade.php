<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizAttemptGrade
 *
 * @property int $id
 * @property int $attempt_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Quiz\Entities\QuizAttempts|null $attempt
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptGrade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptGrade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptGrade query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptGrade whereAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptGrade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptGrade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptGrade whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizAttemptGrade whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizAttemptGrade extends Model
{
    protected $table = 'el_quiz_attempt_grades';
    protected $fillable = [
        'attempt_id',
        'status'
    ];
    
    public function attempt() {
        return $this->hasOne('Modules\Quiz\Entities\QuizAttempts', 'id', 'attempt_id');
    }
}
