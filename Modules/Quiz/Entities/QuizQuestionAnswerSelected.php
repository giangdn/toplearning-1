<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizQuestionAnswerSelected
 *
 * @property int $id
 * @property int $template_id
 * @property int $question_id
 * @property int $qindex
 * @property string $name
 * @property string $type
 * @property int|null $category_id
 * @property int $qqcategory_id
 * @property float|null $score_group
 * @property int $multiple
 * @property float $max_score
 * @property float $score
 * @property string $text_essay
 * @property string|null $grading_comment
 * @property string|null $answer
 * @property string|null $matching
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereGradingComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereMatching($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereQindex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereQqcategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereScoreGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereTextEssay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestionAnswerSelected whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $file_essay
 * @method static \Illuminate\Database\Eloquent\Builder|QuizQuestionAnswerSelected whereFileEssay($value)
 */
class QuizQuestionAnswerSelected extends Model
{
    protected $table = 'el_quiz_question_answer_selected';
    protected $primaryKey = 'id';
    protected $fillable = [
        'template_id',
        'question_id',
        'qindex',
        'name',
        'type',
        'category_id',
        'qqcategory_id',
        'score_group',
        'multiple',
        'max_score',
        'answer',
        'text_essay',
        'matching',
        'score',
    ];

    public static function updateCoreQuestion($id) {
        $question = self::where('id', '=', $id);
        if (!$question->exists()) {
            return false;
        }
        $question = $question->first();
        if ($question->type == 'multiple-choise') {
            if ($question->answer){
                $answer_selected = explode(',', trim(str_replace('"', '', $question->answer), '[]'));

                if ($question->multiple == 0){
                    $selected_answer = QuestionAnswer::whereIn('id', $answer_selected)
                        ->where('question_id', '=', $question->question_id)
                        ->where('correct_answer', '=', 1)
                        ->count();

                    $score = ($question->score_group * $question->max_score) * $selected_answer;
                }
                if ($question->multiple == 1){
                    $count_answer = QuestionAnswer::where('question_id', '=', $question->question_id)->count();
                    $correct_answer = QuestionAnswer::where('question_id', '=', $question->question_id)->where('percent_answer', '>', 0)->count();
                    $selected = QuestionAnswer::where('question_id', '=', $question->question_id)
                        ->whereIn('id', $answer_selected)->get();

                    if ($selected->count() == $count_answer && $correct_answer < $count_answer){
                        $score = 0;
                    }else{
                        $score = 0;
                        foreach ($selected as $item){
                            $score += (($question->score_group * $question->max_score) * $item->percent_answer ) / 100;
                        }
                    }
                }

                $question->score = $score;
                return $question->save();
            }else{
                $question->score = 0;
                return $question->save();
            }
        }
        if ($question->type == 'matching'){
            $score = 0;
            if ($question->matching){
                $matching_select = json_decode($question->matching);

                $answers = QuestionAnswer::where('question_id', '=', $question->question_id)->get();
                $count = 0;
                foreach ($answers as $answer){
                    if ($matching_select->{$answer->id} == $answer->matching_answer){
                        $count += 1;
                    }
                }
                if ($count == $answers->count()){
                    $score = ($question->score_group * $question->max_score);
                }
            }

            $question->score = $score;
            return $question->save();
        }

        return false;
    }

    public static function getQuestionInArray($template_id, $qids) {
        return self::where('template_id', '=', $template_id)
            ->whereIn('id', $qids)
            ->get();
    }
    
}
