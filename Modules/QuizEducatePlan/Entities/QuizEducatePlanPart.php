<?php

namespace Modules\QuizEducatePlan\Entities;

use App\Profile;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\QuizEducatePlan\Entities\QuizEducatePlanPart
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanPart whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\QuizEducatePlan\Entities\QuizTeacher[] $quizTeachers
 * @property-read int|null $quiz_teachers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Profile[] $users
 * @property-read int|null $users_count
 */
class QuizEducatePlanPart extends Model
{
    protected $table = 'el_quiz_educate_plan_part';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'start_date',
        'end_date'
    ];


    public function quizTeachers()
    {
        return $this->hasMany(QuizEducatePlanTeacher::class,'quiz_id','quiz_id');
    }
}
