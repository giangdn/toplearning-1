<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizUserError
 *
 * @property int $id
 * @property int $attempt_id
 * @property int $quiz_id
 * @property int $part_id
 * @property int $user_id
 * @property int $type
 * @property int $attempt
 * @property string $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Modules\Quiz\Entities\Quiz|null $quiz
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserError newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserError newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserError query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserError whereAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserError whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserError wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserError whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserError whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserError whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUserError whereAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUserError whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUserError whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUserError whereUpdatedAt($value)
 */

class QuizUserError extends Model
{
    protected $table = 'el_quiz_error';
    protected $primaryKey = 'id';
    protected $fillable = [
        'attempt_id',
        'quiz_id',
        'part_id',
        'user_id',
        'type',
        'attempt',
        'note',
    ];
}
