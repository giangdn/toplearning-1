<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuizQuestion
 *
 * @property int $id
 * @property int $quiz_id
 * @property int|null $question_id
 * @property int|null $qcategory_id
 * @property int $random
 * @property int $num_order
 * @property float $max_score
 * @property int $qqcategory
 * @property \Modules\Quiz\Entities\Question|null $question
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereNumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereQcategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereQqcategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereRandom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizTemplatesQuestion extends Model
{
    protected $table = 'el_quiz_templates_question';
    protected $fillable = [
        'quiz_id',
        'question_id',
        'qcategory_id',
        'random',
        'max_score',
    ];

    public function question() {
        return $this->hasOne('Modules\Quiz\Entities\Question', 'id', 'question_id');
    }

    public static function getAttributeName() {
        return [
            'quiz_id' => 'Kỳ thi',
            'question_id' => 'Câu hỏi',
            'qcategory_id' => 'Danh mục câu hỏi',
            'random' => 'Ngẫu nhiên',
            'max_score' => 'Điểm tối đa',
        ];
    }

    public static function getMaxOrder($quiz_id) {
        $query = self::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->orderBy('num_order', 'desc');
        if ($query->exists()) {
            return $query->first()->num_order;
        }

        return 0;
    }

    public static function getQuestions($quiz_id) {
        $query = self::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->orderBy('num_order', 'asc');
        if ($query->exists()) {
            return $query->get();
        }

        return [];
    }

    public static function getQuestions2($quiz_id, $category = 0) {
        $query = \DB::query();
        $query->select([
            'a.id',
            'a.qcategory_id',
            'a.num_order',
            'a.max_score',
            'b.name AS question_name',
            'c.name AS category_name'
        ])
            ->from('el_quiz_templates_question AS a')
            ->leftJoin('el_question AS b', 'b.id', '=', 'a.question_id')
            ->leftJoin('el_question_category AS c', 'c.id', '=', 'a.qcategory_id')
            ->where('quiz_id', '=', $quiz_id)
            ->where('qqcategory', '=', $category)
            ->orderBy('num_order', 'asc');

        if ($query->exists()) {
            return $query->get();
        }

        return [];
    }

    public static function getArrayQuestions($quiz_id) {
        $query = self::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->whereNotNull('question_id');
        if ($query->exists()) {
            return $query->pluck('question_id')->toArray();
        }

        return [];
    }

    public static function countQuestion($quiz_id, $cat_id) {
        $query = self::query();
        $query->where('qcategory_id', '=', $cat_id);
        $query->where('quiz_id', '=', $quiz_id);
        return $query->count('id');
    }
    public static function getTotalScore($quiz_id) {
        $query = self::where('quiz_id', '=', $quiz_id);
        if ($query->exists()) {
            return $query->sum('max_score');
        }
        return 0;
    }

    public static function SumMaxScoreByGroup($quiz_id, $qqcate_id){
        $query = self::where('quiz_id', '=', $quiz_id)->where('qqcategory','=', $qqcate_id);
        if ($query->exists()) {
            return $query->sum('max_score');
        }
        return 0;
    }

    public static function randomQuestionUnique($cate_id, $num, $quiz_id,$template_id)
    {
//        $question_randed = QuizTemplateQuestionRanded::query()->where('template_id','=',$template_id)->where('quiz_id','=',$quiz_id)->value('question_rand');
        $question_randed = QuizTemplateQuestionRand::query()
            ->select(["question_id"])
            ->where('template_id','=',$template_id)
            ->where('quiz_id','=',$quiz_id)
            ->pluck('question_id')->toArray();

        $query = \DB::query();
        $query->from('el_question');
        $query->where('category_id','=',$cate_id);
        if ($question_randed)
            $query->whereNotIn('id', $question_randed);
        $query->limit($num)->inRandomOrder();
        return $query->first();
    }
}
