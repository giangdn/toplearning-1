<?php

namespace Modules\Survey\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyTemplate2
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate2 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate2 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate2 query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate2 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate2 whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate2 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate2 whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate2 whereSurveyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate2 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate2 whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Survey\Entities\SurveyQuestionCategory2[] $category
 * @property-read int|null $category_count
 */
class SurveyTemplate2 extends BaseModel
{
    protected $table = 'el_survey_template2';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'survey_id',
        'created_by',
        'updated_by',
        'unit_by'
    ];

    public static function getAttributeName() {
        return [
            'name' => 'Tên mẫu',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor'),
        ];
    }

    public function category()
    {
        return $this->hasMany('Modules\Survey\Entities\SurveyQuestionCategory2','template_id');
    }
}
