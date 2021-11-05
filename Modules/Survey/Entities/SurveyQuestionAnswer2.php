<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyQuestionAnswer2
 *
 * @property int $id
 * @property string|null $name
 * @property int $question_id
 * @property int $is_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer2 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer2 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer2 query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer2 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer2 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer2 whereIsText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer2 whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer2 whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionAnswer2 whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SurveyQuestionAnswer2 extends Model
{
    protected $table = 'el_survey_template2_question_answer';
    protected $fillable = [
        'code',
        'name',
        'question_id',
        'is_text',
        'is_row',
        'survey_id',
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
