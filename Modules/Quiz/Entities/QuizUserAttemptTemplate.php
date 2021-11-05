<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizUserAttemptTemplate
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $part_id
 * @property int $user_id
 * @property int $user_type
 * @property string $template_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserAttemptTemplate whereUserType($value)
 * @mixin \Eloquent
 */
class QuizUserAttemptTemplate extends Model
{
    protected $table = 'el_quiz_user_attempt_template';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'quiz_id',
        'part_id',
        'user_id',
        'user_type',
        'template_id',
        'attempt_id'
    ];
}
