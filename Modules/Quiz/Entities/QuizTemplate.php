<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserHistory;

/**
 * Modules\Quiz\Entities\QuizTemplate
 *
 * @property int $id
 * @property int $quiz_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplate whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizTemplate extends Model{
    protected $table = 'el_quiz_template';
    protected $primaryKey = 'id';
    protected $fillable = [];

    public static function selectTemplateQuizRand($quiz_id, $part_id)
    {
        $quiz = Quiz::find($quiz_id);
        $user = Quiz::getUserId();
        $user_type = Quiz::getUserType();
        $arrayRemove = QuizUserAttemptTemplate::where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user)
            ->where('user_type', '=', $user_type)
            ->pluck('template_id')->toArray();

        $check_quiz_question = QuizQuestion::where('quiz_id', '=', $quiz_id)->where('random', '=', 1)->first();
        if ($check_quiz_question || $quiz->shuffle_question == 1) {
            $array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        } else {
            $array = array(1);
        }

        $arrayRandom = array_diff($array, $arrayRemove);

        if (empty($arrayRandom))
            $randNumber = 1;
        else {
            $rand_keys = array_rand($arrayRandom);
            $randNumber = $arrayRandom[$rand_keys];
        }

        $template = new QuizTemplate();
        $template->quiz_id = $quiz_id;
        if (!$template->save()) {
            return false;
        }
        self::insertTemplateQuestionFromRand($quiz_id, $template->id, $randNumber);
        self::insertUserAttemptTemplate($quiz_id, $part_id, $randNumber);
        return $template->id;
    }

    public static function insertUserAttemptTemplate($quiz_id, $part_id, $template_id)
    {
        $user = Quiz::getUserId();
        $user_type = Quiz::getUserType();
        QuizUserAttemptTemplate::create([
            'quiz_id' => $quiz_id,
            'part_id' => $part_id,
            'user_id' => $user,
            'user_type' => $user_type,
            'template_id' => $template_id,
        ]);

        $count = QuizUserAttemptTemplate::where('quiz_id','=',$quiz_id)->where('user_id','=',$user)->where('part_id','=',$part_id)->where('user_type', '=', $user_type)->count();
        if ($count >= 10){
            QuizUserAttemptTemplate::where('quiz_id','=',$quiz_id)->where('user_id','=',$user)->where('part_id','=',$part_id)->where('user_type', '=', $user_type)->delete();
        }
    }

    public static function insertTemplateQuestionFromRand($quiz_id, $template_id, $randNumber)
    {
        $quiz = Quiz::find($quiz_id);
        /***************insert question*********************/
        $select = QuizTemplateQuestionRand::selectRaw($template_id . ', question_id, qindex, name, type, category_id, qqcategory_id, score_group, multiple, max_score, now(), now()')
            ->where('quiz_id', '=', $quiz_id)->where('template_id', '=', $randNumber);
        if ($quiz->shuffle_question == 1){
            $select = $select->inRandomOrder();
        }

        \DB::table('el_quiz_question_answer_selected')->insertUsing(['template_id', 'question_id', 'qindex', 'name', 'type', 'category_id', 'qqcategory_id', 'score_group', 'multiple', 'max_score', 'created_at', 'updated_at'], $select);

        //\DB::table('el_quiz_question_answer_selected')->where('template_id', '=', $template_id)->dd();
        /********************insert answer*****************/

        /*$prefix = \DB::getTablePrefix();
        $select = QuestionAnswer::query()
            ->selectRaw($prefix.'b.id as question_id,'.$prefix.'a.title,'.$prefix.'a.correct_answer,'.$prefix.'a.percent_answer,'.$prefix.'a.feedback_answer,'.$prefix.'a.matching_answer')
            ->from('el_question_answer as a')
            ->leftJoin('el_quiz_template_question as b', 'b.question_id', '=', 'a.question_id')
            ->where('b.template_id', '=', $template_id)
            ->orderBy('b.id');
        if ($quiz->shuffle_answers == 1)
            $select->inRandomOrder();

        \DB::table('el_quiz_template_question_answer')->insertUsing(['question_id','title','correct_answer','percent_answer','feedback_answer','matching_answer'],$select);*/
    }

    /*Tạo đề*/
    public static function createTemplateQuiz($quiz_id)
    {
        $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();
        $check_question_rand = QuizTemplateQuestionRand::query()->where('quiz_id', '=', $quiz_id)->first();
        if ($check_question_rand) {
            /**************update question template rand*************/
            QuizTemplateQuestionRand::updateAttributeQuestion($quiz_id);
            /************************************/

            $sum_max_score = QuizQuestion::getTotalScore($quiz_id);
            $ques_score = $sum_max_score > 0 ? ($quiz->max_score / $sum_max_score) : 0;

            QuizTemplateQuestionRand::query()->where('quiz_id', '=', $quiz_id)->update(['score_group' => $ques_score]);
            $qqcategorys = QuizQuestionCategory::where('quiz_id', '=', $quiz_id)->orderBy('num_order', 'asc')->get();
            if ($qqcategorys) {
                foreach ($qqcategorys as $qqcategory) {
                    $sum_max_score = $qqcategory->percent_group > 0 ? QuizQuestion::SumMaxScoreByGroup($quiz_id, $qqcategory->id) : QuizQuestion::getTotalScore($quiz_id);
                    $ques_score = $qqcategory->percent_group > 0 ? ($quiz->max_score * $qqcategory->percent_group / 100) / ($sum_max_score ? $sum_max_score : 1) : ($quiz->max_score / $sum_max_score);
                    QuizTemplateQuestionRand::query()->where('quiz_id', '=', $quiz_id)->where('qqcategory_id', '=', $qqcategory->id)->update(['score_group' => $ques_score]);
                }
            }
        } else {
            $check_quiz_question = QuizQuestion::where('quiz_id', '=', $quiz_id)->where('random', '=', 1)->first();

            if ($check_quiz_question || $quiz->shuffle_question == 1) {
                $num_template_question_build = 10;
            } else {
                $num_template_question_build = 1;
            }

            self::deleteQuizTemplateQuestionRand($quiz_id);
            $questions_norand = \DB::query()
                ->select([
                    'a.id',
                    'a.quiz_id',
                    'a.qcategory_id',
                    'a.qqcategory',
                    'a.question_id',
                    'a.num_order',
                    'a.max_score',
                    'b.name',
                    'b.type',
                    'b.multiple'
                ])
                ->from('el_quiz_question as a')
                ->join('el_question as b', 'a.question_id', '=', 'b.id')
                ->where('a.quiz_id', '=', $quiz_id)
                ->where('a.random', '=', 0)
                ->get();

            foreach ($questions_norand as $key => $question) {
                for ($i = 1; $i <= $num_template_question_build; $i++) {
                    $quiz_template_question_rand = new QuizTemplateQuestionRand();
                    $quiz_template_question_rand->template_id = $i;
                    $quiz_template_question_rand->quiz_id = $quiz_id;
                    $quiz_template_question_rand->qindex = $question->num_order;
                    $quiz_template_question_rand->question_id = $question->question_id;
                    $quiz_template_question_rand->quiz_question_id = $question->id;
                    $quiz_template_question_rand->name = $question->name;
                    $quiz_template_question_rand->type = $question->type;
                    $quiz_template_question_rand->category_id = $question->qcategory_id;
                    $quiz_template_question_rand->qqcategory_id = $question->qqcategory;
                    $quiz_template_question_rand->multiple = $question->multiple;
                    $quiz_template_question_rand->max_score = $question->max_score;
                    $quiz_template_question_rand->save();
                }
            }
            $query = QuizQuestion::where('quiz_id', '=', $quiz_id)->where('random', '=', 1);
            $questions = $query->get();
            foreach ($questions as $key => $question) {
                for ($i = 1; $i <= $num_template_question_build; $i++) {
                    $question_random = QuizQuestion::randomQuestionUnique($question->qcategory_id, 1, $quiz_id, $i);
                    if (!$question_random) {
                        continue;
                    }

                    $quiz_template_question_rand = new QuizTemplateQuestionRand();
                    $quiz_template_question_rand->template_id = $i;
                    $quiz_template_question_rand->quiz_id = $quiz_id;
                    $quiz_template_question_rand->qindex = $question->num_order;
                    $quiz_template_question_rand->question_id = $question_random->id;
                    $quiz_template_question_rand->quiz_question_id = $question->id;
                    $quiz_template_question_rand->name = $question_random->name;
                    $quiz_template_question_rand->type = $question_random->type;
                    $quiz_template_question_rand->category_id = $question->qcategory_id;
                    $quiz_template_question_rand->qqcategory_id = $question->qqcategory;
                    $quiz_template_question_rand->multiple = $question_random->multiple;
                    $quiz_template_question_rand->max_score = $question->max_score;
                    $quiz_template_question_rand->save();
                }
            }

            $sum_max_score = QuizQuestion::getTotalScore($quiz_id);
            $ques_score = $sum_max_score > 0 ? ($quiz->max_score / $sum_max_score) : 0;

            QuizTemplateQuestionRand::query()->where('quiz_id', '=', $quiz_id)->update(['score_group' => $ques_score]);
            $qqcategorys = QuizQuestionCategory::where('quiz_id', '=', $quiz_id)->orderBy('num_order', 'asc')->get();
            if ($qqcategorys) {
                foreach ($qqcategorys as $qqcategory) {
                    $model = new QuizTemplateQuestionCategoryRand();
                    $model->quiz_id = $quiz_id;
                    $model->name = $qqcategory->name;
                    $model->num_order = $qqcategory->num_order;
                    $model->percent_group = $qqcategory->percent_group;
                    $model->qqcategory = $qqcategory->id;
                    $model->save();

                    $sum_max_score = $qqcategory->percent_group > 0 ? QuizQuestion::SumMaxScoreByGroup($quiz_id, $qqcategory->id) : QuizQuestion::getTotalScore($quiz_id);
                    $ques_score = $qqcategory->percent_group > 0 ? ($quiz->max_score * $qqcategory->percent_group / 100) / ($sum_max_score ? $sum_max_score : 1) : ($quiz->max_score / $sum_max_score);
                    QuizTemplateQuestionRand::query()->where('quiz_id', '=', $quiz_id)->where('qqcategory_id', '=', $qqcategory->id)->update(['score_group' => $ques_score]);
                }
            }
        }

    }

    public static function deleteQuizTemplateQuestionRand($quiz_id)
    {
        QuizTemplateQuestionRand::query()->where('quiz_id', '=', $quiz_id)->delete();
    }

    /*Dủng in đề*/
    public static function createTemplate($quiz_id)
    {
        $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();
        $template = new QuizTemplate();
        $template->quiz_id = $quiz_id;
        if (!$template->save()) {
            return false;
        }

        $query = QuizQuestion::where('quiz_id', '=', $quiz_id)
            ->where('qqcategory', '=', 0);

        if ($quiz->shuffle_question == 1) {
            $query->inRandomOrder();
        } else {
            $query->orderBy('num_order', 'asc');
        }

        $index = 1;
        $questions = $query->get();
        foreach ($questions as $question) {
            self::createTempQuestion($quiz, $template->id, $question, $index);
            $index += 1;
        }
        $sum_max_score = QuizTemplateQuestion::getTotalScore($template->id);
        $ques_score = $sum_max_score > 0 ? ($quiz->max_score / $sum_max_score) : 0;

        QuizTemplateQuestion::where('template_id', '=', $template->id)->update(['score_group' => $ques_score]);

        QuizQuestionCategory::where('quiz_id', '=', $quiz_id)
            ->orderBy('num_order', 'asc')
            ->chunkById(20, function ($qqcategorys) use ($template, $quiz, &$index) {
                foreach ($qqcategorys as $qqcategory) {
                    $model = new QuizTemplateQuestionCategory();
                    $model->template_id = $template->id;
                    $model->name = $qqcategory->name;
                    $model->num_order = $qqcategory->num_order;
                    $model->percent_group = $qqcategory->percent_group;
                    $model->save();

                    $query = QuizQuestion::where('quiz_id', '=', $quiz->id)
                        ->where('qqcategory', '=', $qqcategory->id);

                    if ($quiz->shuffle_question == 1) {
                        $query->inRandomOrder();
                    } else {
                        $query->orderBy('num_order', 'asc');
                    }

                    $questions = $query->get();
                    foreach ($questions as $question) {
                        self::createTempQuestion($quiz, $template->id, $question, $index, $model->id);
                        $index += 1;
                    }
                    $sum_max_score = QuizTemplateQuestion::SumMaxScoreByGroup($template->id, $model->id);
                    $ques_score = ($quiz->max_score * $model->percent_group / 100) / ($sum_max_score > 0 ? $sum_max_score : 1);
                    QuizTemplateQuestion::where('template_id', '=', $template->id)
                        ->where('qqcategory_id', '=', $model->id)->update(['score_group' => $ques_score]);
                }
            }, 'id');

        return $template->id;
    }

    public static function createTempQuestion($quiz, $template_id, $quiz_question, $index, $qqcategory_id = 0){
        $question_info = null;
        if ($quiz_question->random == 0) {
            $question_info = Question::where('id', '=', $quiz_question->question_id)->first();
        }

        if ($quiz_question->random == 1) {
            $question_info = Question::where('category_id', '=', $quiz_question->qcategory_id)
                ->whereNotIn('id', function ($subquery) use ($template_id) {
                    $subquery->select(['question_id'])
                        ->from('el_quiz_template_question')
                        ->where('template_id', '=', $template_id);
                })
                ->inRandomOrder()
                ->first();
        }

        if (empty($question_info)) {
            return false;
        }

        $count_index = QuizTemplateQuestion::where('template_id', '=', $template_id)->count('qindex');

        $tempquestion = new QuizTemplateQuestion();
        $tempquestion->template_id = $template_id;
        $tempquestion->question_id = $question_info->id;
        $tempquestion->name = $question_info->name;
        $tempquestion->type = $question_info->type;
        $tempquestion->category_id = $question_info->category_id;
        $tempquestion->multiple = $question_info->multiple;
        $tempquestion->max_score = $quiz_question->max_score;
        $tempquestion->qindex = $count_index + 1;
        $tempquestion->qqcategory_id = $qqcategory_id;

        if ($tempquestion->save()) {
            self::createTempQuestionArswer($quiz, $tempquestion->id, $question_info->id);
        }
    }

    public static function createTempQuestionArswer($quiz, $tquestion_id, $question_id){
        $query = QuestionAnswer::where('question_id', '=', $question_id);
        if ($quiz->shuffle_answers == 1) {
            $query->inRandomOrder();
        }
        $answers = $query->get();
        if ($answers) {
            foreach ($answers as $answer) {
                $tqanswer = new QuizTemplateQuestionAnswer();
                $tqanswer->question_id = $tquestion_id;
                $tqanswer->title = $answer->title;
                $tqanswer->correct_answer = $answer->correct_answer;
                $tqanswer->feedback_answer = $answer->feedback_answer;
                $tqanswer->matching_answer = $answer->matching_answer;
                $tqanswer->percent_answer = $answer->percent_answer;
                $tqanswer->save();
            }

            return true;
        }

        return false;
    }

    public static function deleteTemplate($template_id)
    {
        $template_questions = QuizTemplateQuestion::where('template_id', '=', $template_id)->get();
        foreach ($template_questions as $template_question){
            QuizTemplateQuestionAnswer::where('question_id', '=', $template_question->id)->delete();
        }

        QuizTemplateQuestion::where('template_id', '=', $template_id)->delete();
        QuizTemplateQuestionCategory::where('template_id', '=', $template_id)->delete();
        QuizTemplate::find($template_id)->delete();
    }
    /*********************/
    public static function updateGradeQuiz($quiz_id, $user_id, $user_type)
    {
        $quiz = Quiz::where('id', '=', $quiz_id)->first();
        if (empty($quiz)) {
            return false;
        }

        $grade = 0;
        if ($quiz->grade_methor == 1) {
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('type', '=', $user_type)
                ->select(\DB::raw('MAX(sumgrades) AS total_grade'))
                ->first();
            if ($sumgrade) {
                $grade = $sumgrade->total_grade;
            }
        }

        if ($quiz->grade_methor == 2) {
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('type', '=', $user_type)
                ->select(\DB::raw('AVG(sumgrades) AS total_grade'))
                ->first();
            if ($sumgrade) {
                $grade = $sumgrade->total_grade;
            }
        }

        if ($quiz->grade_methor == 3) {
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('type', '=', $user_type)
                ->where('attempt', '=', 1)
                ->first();
            if ($sumgrade) {
                $grade = $sumgrade->sumgrades;
            }
        }

        if ($quiz->grade_methor == 4) {
            $sumgrade = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('type', '=', $user_type)
                ->whereColumn('attempt', '=', function ($subquery) use ($quiz_id, $user_id) {
                    $subquery->select(\DB::raw('MAX(attempt)'))
                        ->where('quiz_id', '=', $quiz_id)
                        ->where('user_id', '=', $user_id)
                        ->first();
                })
                ->first();
            if ($sumgrade) {
                $grade = $sumgrade->sumgrades;
            }
        }

        $result = QuizResult::where('quiz_id', '=', $quiz_id)->where('user_id', '=', $user_id);

        if (!$result->exists()) {
            $result = new QuizResult();
            $result->quiz_id = $quiz_id;
            $result->user_id = $user_id;
            $result->type = $user_type;
            $result->grade = $grade;
            $result->result = ($grade >= $quiz->pass_score) ? 1 : 0;
            return $result->save();
        } else {
            $result = $result->first();
            $result->grade = $grade;
            $result->result = ($grade >= $quiz->pass_score) ? 1 : 0;
            return $result->save();
        }
    }

    public static function canCreateTemplate($quiz_id,$part, $user_id)
    {
        $quiz = Quiz::where('id', '=', $quiz_id)->first();
        $user_attempt = QuizAttempts::countQuizAttempt($quiz_id, $user_id);

        $attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->first();

        if (empty($quiz)) {
            return false;
        }
        if (time()< strtotime( $part->start_date))
            return false;
        if ($user_attempt < $quiz->max_attempts || $quiz->max_attempts == 0 || (($attempt->timestart + ($quiz->limit_time * 60)) > time() && $attempt->timefinish == 0)) {
            return true;
        }

        return false;
    }
}
