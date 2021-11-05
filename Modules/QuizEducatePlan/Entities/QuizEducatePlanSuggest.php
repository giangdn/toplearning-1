<?php

namespace Modules\QuizEducatePlan\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\QuizEducatePlan\Entities\QuizEducatePlanSuggest
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizEducatePlanSuggest whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class QuizEducatePlanSuggest extends Model
{
	protected $table="el_quiz_educate_plan_suggest";
    protected $fillable = [
        'name',
        'created_by',
        'updated_by'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên đề xuất',
            'created_by'=>'Ngày tạo',
            'updated_by'=>'Ngày sửa'
        ];
    }

}
