<?php

namespace Modules\Survey\Entities;

use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyObject
 *
 * @property int $id
 * @property int $survey_id
 * @property int|null $title_id
 * @property int|null $unit_id
 * @property int|null $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyObject whereSurveyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyObject whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyObject whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyObject whereUserId($value)
 * @mixin \Eloquent
 */
class SurveyObject extends Model
{
    use ChangeLogs;
    protected $table = 'el_survey_object';
    protected $fillable = [
        'survey_id',
        'unit_id',
        'title_id',
        'user_id',
    ];
    protected $primaryKey = 'id';

    public $timestamps = null;

    public static function checkObjectUnit ($survey_id, $unit_id){
        $query = self::query();
        $query->where('unit_id', '=', $unit_id);
        $query->where('survey_id', '=', $survey_id);
        return $query->exists();
    }
    public static function checkObjectTitle ($survey_id, $title_id){
        $query = self::query();
        $query->where('title_id', '=', $title_id);
        $query->where('survey_id', '=', $survey_id);
        return $query->exists();
    }
}
