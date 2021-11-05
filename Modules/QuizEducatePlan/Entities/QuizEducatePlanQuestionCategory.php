<?php

namespace Modules\QuizEducatePlan\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $name
 * @property int $num_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory whereNumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $percent_group
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\QuizEducatePlan\Entities\QuizEducatePlanQuestionCategory wherePercentGroup($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\QuizEducatePlan\Entities\QuizQuestion[] $questions
 * @property-read int|null $questions_count
 */
class QuizEducatePlanQuestionCategory extends Model
{
    protected $table = 'el_quiz_educate_plan_question_category';
    protected $fillable = [
        'name',
        'num_order',
        'percent_group'
    ];
    
    public function questions() {
        return $this->hasMany('Modules\Quiz\Entities\QuizQuestion', 'qqcategory', 'id');
    }
    
    public function sumMaxScore(){
        $max_score = $this->questions()
            ->sum('max_score');
    
        if ($max_score > 0) {
            return $max_score;
        }
    
        return 0;
    }

    public static function sumMaxScoreByQuizID($quiz_id){
        $quiz_ques_cate = QuizEducatePlanQuestionCategory::where('quiz_id', '=', $quiz_id)->get();
        $total = 0;

        foreach ($quiz_ques_cate as $item){
            $total += $item->percent_group;
        }

        return $total;
    }
}
