<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyUserCategory
 *
 * @property int $id
 * @property int $survey_user_id
 * @property int $category_id
 * @property string $category_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserCategory whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserCategory whereSurveyUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUserCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Survey\Entities\SurveyUserQuestion[] $questions
 * @property-read int|null $questions_count
 */
class SurveyUserCategory extends Model
{
    protected $table = 'el_survey_user_category';
    protected $fillable = [
        'survey_user_id',
        'category_id',
        'category_name',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'survey_user_id' => 'Nhân viên khảo sát',
            'category_id' => 'Danh mục',
            'category_name' => 'Tên danh mục',
        ];
    }

    public function questions()
    {
        return $this->hasMany('Modules\Survey\Entities\SurveyUserQuestion', 'survey_user_category_id');
    }
}
