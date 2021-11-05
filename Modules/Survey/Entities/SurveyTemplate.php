<?php

namespace Modules\Survey\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyTemplate
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyTemplate whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Survey\Entities\SurveyQuestionCategory[] $category
 * @property-read int|null $category_count
 */
class SurveyTemplate extends BaseModel
{
    protected $table = 'el_survey_template';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
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
        return $this->hasMany('Modules\Survey\Entities\SurveyQuestionCategory','template_id');
    }
}
