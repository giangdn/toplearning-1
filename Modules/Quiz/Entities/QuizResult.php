<?php

namespace Modules\Quiz\Entities;

use App\CacheModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizResult
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $user_id
 * @property int $type
 * @property float|null $grade
 * @property float|null $reexamine
 * @property string|null $attach_file
 * @property int $timecompleted
 * @property int $result
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereAttachFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereReexamine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereTimecompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizResult whereUserId($value)
 * @mixin \Eloquent
 */
class QuizResult extends CacheModel
{
    protected $table = 'el_quiz_result';
    protected $fillable = [
        'quiz_id',
        'user_id',
        'type',
        'grade',
        'timecompleted',
        'reexamine',
        'attach_file',
        'result',
    ];
    protected $primaryKey = 'id';
}
