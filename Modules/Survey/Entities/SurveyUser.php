<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyUser
 *
 * @property int $id
 * @property int $template_id
 * @property int $user_id
 * @property int $survey_id
 * @property string $more_suggestions
 * @property int $send
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser whereMoreSuggestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser whereSend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser whereSurveyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\SurveyUser whereUserId($value)
 * @mixin \Eloquent
 */
class SurveyUser extends Model
{
    protected $table = 'el_survey_user';
    protected $fillable = [
        'template_id',
        'user_id',
        'survey_id',
        'send',
        'more_suggestions',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'template_id' => 'Mẫu khảo sát',
            'user_id' => 'Người khảo sát',
            'survey_id' => 'Khảo sát',
        ];
    }
}
