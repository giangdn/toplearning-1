<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyUserQuestion
 *
 * @property int $id
 * @property int $survey_user_category_id
 * @property int $question_id
 * @property string $question_name
 * @property string $answer_essay
 * @property string $type
 * @property int $multiple
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion whereAnswerEssay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion whereMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion whereQuestionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion whereSurveyUserCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Survey\Entities\SurveyUserAnswer[] $answers
 * @property-read int|null $answers_count
 */
class SurveyUserQuestion extends Model
{
    protected $table = 'el_survey_user_question';
    protected $fillable = [
        'survey_user_category_id',
        'question_id',
        'question_code',
        'question_name',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'survey_user_category_id' => 'Danh mục khảo sát',
            'question_id' => 'Câu hỏi',
            'question_name' => 'Tên câu hỏi',
        ];
    }

    public function answers()
    {
        return $this->hasMany('Modules\Survey\Entities\SurveyUserAnswer', 'survey_user_question_id');
    }

    public function answers_matrix()
    {
        return $this->hasMany('Modules\Survey\Entities\SurveyUserAnswerMatrix', 'survey_user_question_id');
    }
}
