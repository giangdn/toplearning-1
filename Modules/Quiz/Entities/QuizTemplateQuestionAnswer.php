<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizTemplateQuestionAnswer
 *
 * @property int $id
 * @property int $question_id
 * @property string $title
 * @property int $is_text
 * @property int $correct_answer
 * @property int $selected
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer whereIsText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer whereSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float|null $percent_answer
 * @property string|null $feedback_answer
 * @property string|null $matching_answer
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer whereFeedbackAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer whereMatchingAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionAnswer wherePercentAnswer($value)
 */
class QuizTemplateQuestionAnswer extends Model
{
    protected $table = 'el_quiz_template_question_answer';
    protected $primaryKey = 'id';
    protected $fillable = ['selected'];
}
