<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizResult;

/**
 * Modules\Online\Entities\OnlineCourseActivityQuiz
 *
 * @property-read \Modules\Online\Entities\OnlineCourseActivity|null $course_activity
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityQuiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityQuiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityQuiz query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $course_id
 * @property int $quiz_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityQuiz whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityQuiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityQuiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityQuiz whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityQuiz whereUpdatedAt($value)
 */
class OnlineCourseActivityQuiz extends Model
{
    protected $table = 'el_online_course_activity_quizzes';
    protected $fillable = [
        'course_id',
        'quiz_id',
    ];
    
    public function course_activity() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourseActivity', 'subject_id', 'id');
    }

    /**
     * Check user completed Activity
     * @param int $user_id
     * @return bool
     * */
    public function checkComplete($user_id) {
        $quiz = Quiz::find($this->quiz_id);
        if (empty($quiz)) {
            return false;
        }

        $score = QuizResult::where('quiz_id', '=', $quiz->id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', 1)
            ->first();

        if ($score){
            return (object) [
                'pass_score' => $quiz->pass_score,
                'score' => isset($score->reexamine) ? $score->reexamine : (isset($score->grade) ? $score->grade : 0),
            ];
        }
        return false;
    }
}
