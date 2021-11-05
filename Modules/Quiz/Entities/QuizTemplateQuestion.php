<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizTemplateQuestion
 *
 * @property int $id
 * @property int $template_id
 * @property int $question_id id câu hỏi trong el_question
 * @property int $qindex index câu hỏi
 * @property string $name
 * @property string $type loại câu hỏi
 * @property int|null $category_id
 * @property int $qqcategory_id
 * @property float|null $score_group
 * @property int $multiple Cho phép chọn nhiều
 * @property float $max_score Điểm tối đa của câu hỏi
 * @property float $score Điểm của thí sinh nhận được
 * @property string|null $text_essay Câu trả lời nếu câu hỏi là tự luận
 * @property string|null $grading_comment Đánh giá của người chấm thi
 * @property string|null $answer
 * @property string|null $matching
 * @property string|null $file_essay
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereFileEssay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereGradingComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereMatching($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereQindex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereQqcategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereScoreGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereTextEssay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTemplateQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizTemplateQuestion extends Model
{
    protected $table = 'el_quiz_template_question';
    protected $fillable =
      [
          'quiz_id',
          'question_id',
          'qcategory_id',
          'random',
          'num_order',
          'max_score',
          'qqcategory',
    ];
   /* public function answers() {
        return $this->hasMany('Modules\Quiz\Entities\QuizTemplateQuestionAnswer','question_id','id');
    }*/

    public static function getTotalQuestion($template_id) {
        return self::where('template_id', '=', $template_id)->count();
    }

    public static function getQuestionInArray($template_id, $qids) {
        return self::where('template_id', '=', $template_id)
            ->whereIn('id', $qids)
            ->get();
    }

    public static function getTotalScore($template_id) {
        $query = self::where('template_id', '=', $template_id);
        if ($query->exists()) {
            return $query->sum('max_score');
        }
        return 0;
    }

    public static function SumMaxScoreByGroup($template_id, $qqcate_id){
        $query = self::where('template_id', '=', $template_id)
        ->where('qqcategory_id','=', $qqcate_id);
        if ($query->exists()) {
            return $query->sum('max_score');
        }
        return 0;
    }

    public static function getGradeUser($template_id) {
        $query = QuizQuestionAnswerSelected::where('template_id', '=', $template_id);
        if ($query->exists()) {
            return $query->sum('score');
        }

        return 0;
    }

    public static function getGradeUserByTeacher($template_id) {
        $query = self::where('template_id', '=', $template_id);
        if ($query->exists()) {
            return $query->sum('score');
        }

        return 0;
    }

    public static function isSelected($question_id, $template_id) {
        $question = self::where('id', '=', $question_id)
            ->where('template_id', '=', $template_id)
            ->first();

        if ($question){
            if ($question->text_essay || $question->answer || $question->matching){
                return true;
            }
            return false;
        }
        return false;
    }

    public static function getGradePerScore() {

    }
}
