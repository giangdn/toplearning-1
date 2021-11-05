<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizSetting
 *
 * @property int $id
 * @property int $quiz_id
 * @property int|null $after_test_review_test
 * @property int|null $after_test_yes_no
 * @property int|null $after_test_score
 * @property int|null $after_test_specific_feedback
 * @property int|null $after_test_general_feedback
 * @property int|null $after_test_correct_answer
 * @property int|null $exam_closed_review_test
 * @property int|null $exam_closed_yes_no
 * @property int|null $exam_closed_score
 * @property int|null $exam_closed_specific_feedback
 * @property int|null $exam_closed_general_feedback
 * @property int|null $exam_closed_correct_answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereAfterTestCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereAfterTestGeneralFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereAfterTestReviewTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereAfterTestScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereAfterTestSpecificFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereAfterTestYesNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereExamClosedCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereExamClosedGeneralFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereExamClosedReviewTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereExamClosedScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereExamClosedSpecificFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereExamClosedYesNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizSetting extends Model
{
    protected $table = 'el_quiz_setting';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quiz_id',
        'after_test_review_test',
        'after_test_yes_no',
        'after_test_score',
        'after_test_specific_feedback',
        'after_test_general_feedback',
        'after_test_correct_answer',
        'exam_closed_review_test',
        'exam_closed_yes_no',
        'exam_closed_score',
        'exam_closed_specific_feedback',
        'exam_closed_general_feedback',
        'exam_closed_correct_answer',

    ];
}
