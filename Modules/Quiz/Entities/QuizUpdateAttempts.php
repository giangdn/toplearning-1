<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizUpdateAttempts
 *
 * @property int $id
 * @property int $attempt_id
 * @property int $quiz_id
 * @property int $part_id
 * @property int $user_id
 * @property int $type
 * @property int $status
 * @property string|null $categories
 * @property string|null $questions
 * @property float|null $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUpdateAttempts whereUserId($value)
 * @mixin \Eloquent
 */
class QuizUpdateAttempts extends Model
{
    protected $table = 'el_quiz_update_attempt';
    protected $primaryKey = 'id';
    protected $fillable = [
        'attempt_id',
        'quiz_id',
        'part_id',
        'user_id',
        'type',
        'status',
        'categories',
        'questions',
        'correct_answers',
        'score',
    ];


}
