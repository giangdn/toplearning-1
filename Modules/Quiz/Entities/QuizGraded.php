<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizGraded
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_type
 * @property int $quiz_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizGraded newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizGraded newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizGraded query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizGraded whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizGraded whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizGraded whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizGraded whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizGraded whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizGraded whereUserType($value)
 * @mixin \Eloquent
 */
class QuizGraded extends Model
{
    protected $table = 'el_quiz_graded';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'user_type',
        'quiz_id',
    ];
}
