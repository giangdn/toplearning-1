<?php

namespace Modules\QuizEducatePlan\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting
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
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereAfterTestCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereAfterTestGeneralFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereAfterTestReviewTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereAfterTestScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereAfterTestSpecificFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereAfterTestYesNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereExamClosedCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereExamClosedGeneralFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereExamClosedReviewTest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereExamClosedScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereExamClosedSpecificFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereExamClosedYesNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizEducatePlanSetting extends Model
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
