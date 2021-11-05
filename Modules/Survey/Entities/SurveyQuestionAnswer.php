<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyQuestionAnswer
 *
 * @property int $id
 * @property string|null $name
 * @property int $question_id
 * @property int $is_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer whereIsText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SurveyQuestionAnswer extends Model
{
    protected $table = 'el_survey_template_question_answer';
    protected $fillable = [
        'code',
        'name',
        'question_id',
        'is_text',
        'is_row',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Câu trả lời',
            'question_id' => 'Câu hỏi',
        ];
    }

    public static function getAnswer($question_id)
    {
        $query = self::query();
        return $query->select(['id', 'name', 'is_text'])
            ->where('question_id', '=', $question_id)
            ->get();
    }
}
