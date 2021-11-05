<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyQuestion
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string $type
 * @property int $multiple
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestion whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestion whereMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Survey\Entities\SurveyQuestionAnswer[] $answers
 * @property-read int|null $answers_count
 */
class SurveyQuestion extends Model
{
    protected $table = 'el_survey_template_question';
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'type',
        'multiple',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Câu hỏi',
            'category_id' => 'Danh mục',
            'type' => 'Loại câu hỏi',
        ];
    }

    public static function getQuestion($category_id)
    {
        $query = self::query();
        return $query->select(['id', 'name', 'type', 'multiple'])
            ->where('category_id', '=', $category_id)
            ->get();
    }

    public function answers()
    {
        return $this->hasMany('Modules\Survey\Entities\SurveyQuestionAnswer','question_id');
    }

    public function answers_matrix()
    {
        return $this->hasMany('Modules\Survey\Entities\SurveyAnswerMatrix','question_id');
    }
}
