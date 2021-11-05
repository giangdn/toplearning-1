<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyQuestionCategory2
 *
 * @property int $id
 * @property int $template_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory2 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory2 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory2 query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory2 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory2 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory2 whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory2 whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory2 whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Survey\Entities\SurveyQuestion2[] $questions
 * @property-read int|null $questions_count
 */
class SurveyQuestionCategory2 extends Model
{
    protected $table = 'el_survey_template2_question_category';
    protected $fillable = [
        'name',
        'template_id',
        'survey_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên danh mục',
            'template_id' => 'Mẫu',
        ];
    }

    public static function getCategoryTemplate($template_id){
        $query = self::query();
        return $query->select(['id', 'name'])
            ->where('template_id', '=', $template_id)
            ->get();
    }

    public function questions()
    {
        return $this->hasMany('Modules\Survey\Entities\SurveyQuestion2','category_id');
    }
}
