<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyQuestionCategory
 *
 * @property int $id
 * @property int $template_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestionCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Survey\Entities\SurveyQuestion[] $questions
 * @property-read int|null $questions_count
 */
class SurveyQuestionCategory extends Model
{
    protected $table = 'el_survey_template_question_category';
    protected $fillable = [
        'name',
        'template_id',
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
        return $this->hasMany('Modules\Survey\Entities\SurveyQuestion','category_id');
    }
}
