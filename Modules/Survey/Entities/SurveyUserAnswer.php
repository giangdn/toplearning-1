<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyUserAnswer
 *
 * @property int $id
 * @property int $survey_user_question_id
 * @property int $answer_id
 * @property string $answer_name
 * @property string $text_answer
 * @property int $is_check
 * @property int $is_text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer whereAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer whereAnswerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer whereIsCheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer whereIsText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer whereSurveyUserQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer whereTextAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SurveyUserAnswer extends Model
{
    protected $table = 'el_survey_user_answer';
    protected $fillable = [
        'survey_user_question_id',
        'answer_id',
        'answer_code',
        'answer_name',
        'text_answer',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'survey_user_question_id' => 'Câu hỏi khảo sát',
            'answer_id' => 'Câu trả lời',
            'answer_name' => 'Tên câu trả lời',
            'text_answer' => 'Nội dung câu trả lời',
        ];
    }
}
