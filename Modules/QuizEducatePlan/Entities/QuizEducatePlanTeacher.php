<?php

namespace Modules\QuizEducatePlan\Entities;

use App\Models\Categories\TrainingTeacher;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $teacher_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanTeacher whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read TrainingTeacher $teacher
 */
class QuizEducatePlanTeacher extends Model
{
    protected $table = 'el_quiz_educate_plan_teacher';
    protected $primaryKey = 'id';
    protected $fillable = [];

    public static function checkExists($quiz_id, $teacher_id) {
        return self::where('quiz_id', '=', $quiz_id)->where('teacher_id', '=', $teacher_id)->exists();
    }

    public static function getTeacherByQuiz($quiz_id) {
        $query = self::query();
        $query->select([
            'b.*'
        ]);
        $query->from('el_quiz_educate_plan_teacher AS a');
        $query->join('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id');
        $query->where('quiz_id', '=', $quiz_id);
        return $query->get();
    }

    public function teacher()
    {
        return $this->belongsTo(TrainingTeacher::class,'teacher_id');
    }
}
