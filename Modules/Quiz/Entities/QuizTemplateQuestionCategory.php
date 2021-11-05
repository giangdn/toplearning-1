<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizTemplateQuestionCategory
 *
 * @property int $id
 * @property int $template_id
 * @property string $name
 * @property int $num_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionCategory whereNumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionCategory whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $percent_group
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplateQuestionCategory wherePercentGroup($value)
 */
class QuizTemplateQuestionCategory extends Model
{
    protected $table = 'el_quiz_template_question_category';
    protected $primaryKey = 'id';
    protected $fillable = [];
}
