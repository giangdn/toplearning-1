<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizQuestionCategory
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $name
 * @property int $num_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereNumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $percent_group
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionCategory wherePercentGroup($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quiz\Entities\QuizQuestion[] $questions
 * @property-read int|null $questions_count
 */
class QuizQuestionCategory extends Model
{
    protected $table = 'el_quiz_question_category';
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
        $quiz_ques_cate = QuizQuestionCategory::where('quiz_id', '=', $quiz_id)->get();
        $total = 0;

        foreach ($quiz_ques_cate as $item){
            $total += $item->percent_group;
        }

        return $total;
    }
}
