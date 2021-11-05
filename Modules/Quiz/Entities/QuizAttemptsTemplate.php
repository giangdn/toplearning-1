<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizAttemptsTemplate
 *
 * @property int $attempt_id
 * @property int $template_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttemptsTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttemptsTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttemptsTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttemptsTemplate whereAttemptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizAttemptsTemplate whereTemplateId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizTemplateQuestion[] $questions
 * @property-read int|null $questions_count
 */
class QuizAttemptsTemplate extends Model
{
    public $timestamps = false;
    protected $table = 'el_quiz_attempts_template';
    
    public function questions() {
        return $this->hasMany('Modules\Quiz\Entities\QuizTemplateQuestion', 'template_id', 'template_id');
    }
}
