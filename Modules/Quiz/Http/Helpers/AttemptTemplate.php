<?php

namespace Modules\Quiz\Http\Helpers;

use Illuminate\Database\Query\Builder;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\ReportCorrectAnswerRate;

class AttemptTemplate
{
    protected $attempt;

    protected $quiz;

    protected $template = [];

    protected $ramdom_questions;

    protected $answer_text = [
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',
        'k',
        'l',
        'm',
        'n',
        'p',
        'q',
    ];

    public function __construct(QuizAttempts $attempt) {
        $this->attempt = $attempt;
        $this->quiz = $attempt->quiz;
        $this->ramdom_questions = [0];
    }

    public function create() {
        $this->mapQuizQuestionCategories();
        $this->mapQuizQuestions();

        if ($this->template) {
            $storage = \Storage::disk('local');
            $attempt_folder = 'quiz/' . $this->quiz->id . '/attempts';

            if (!$storage->exists($attempt_folder)) {
                \File::makeDirectory($storage->path($attempt_folder), 0777, true);
            }

            $this->attempt->save();
            $storage->put($attempt_folder . '/attempt-' . $this->attempt->id . '.json', json_encode($this->template));

            return true;
        }

        return false;
    }

    protected function mapQuizQuestionCategories() {
        $qqcategorys = QuizQuestionCategory::whereQuizId($this->attempt->quiz_id)->orderBy('num_order', 'asc')
            ->get();

        $categories = [];
        foreach ($qqcategorys as $qqcategory) {
            $max_score = $qqcategory->sumMaxScore();
            $per_score = $qqcategory->percent_group > 0 ? ($this->quiz->max_score * $qqcategory->percent_group / 100) / ($max_score ? $max_score : 1) : ($this->quiz->max_score / $max_score);

            $categories[] = [
                'name' => $qqcategory->name,
                'num_order' => $qqcategory->num_order,
                'percent_group' => $qqcategory->percent_group,
                'qqcategory' => $qqcategory->id,
                'max_score' => $max_score,
                'per_score' => $per_score,
            ];
        }

        $this->template['categories'] = $categories;
    }

    protected function mapQuizQuestions() {
        $questions = [];
        $max_score = QuizQuestion::getTotalScore($this->attempt->quiz_id);
        $score_group = $max_score > 0 ? ($this->attempt->quiz->max_score / $max_score) : 0;

        if ($this->quiz->shuffle_question == 1){
            $list_questions = $this->quiz->questions()->inRandomOrder()->get();
        }else{
            $list_questions = $this->quiz->questions;
        }

        $quiz = Quiz::find($this->quiz->id);

        foreach ($list_questions as $key => $question) {
            if ($question->random == 1) {
                $ranrom = Question::where('category_id','=', $question->qcategory_id)
                    ->whereNotIn('id', $this->ramdom_questions)
                    ->whereNotIn('id', function (Builder $builder) {
                        $builder->select(['question_id'])
                            ->from('el_quiz_question')
                            ->where('quiz_id', '=', $this->quiz->id)
                            ->whereNotNull('question_id');
                    })
                    ->inRandomOrder()
                    ->first();

                $questions[$question->id] = [
                    'id' => $question->id,
                    'index' => $key,
                    'qindex' => $question->num_order,
                    'question_id' => $ranrom->id,
                    'name' => $ranrom->name,
                    'type' => $ranrom->type,
                    'category_id' => $ranrom->category_id,
                    'qqcategory_id' => $question->qqcategory,
                    'multiple' => $ranrom->multiple,
                    'score_group' => $score_group,
                    'max_score' => $question->max_score,
                    'answers' => $this->getAnwsersQuestion($ranrom->id),
                    'answer_horizontal' => $ranrom->answer_horizontal,
                ];

                $this->ramdom_questions[] = $ranrom->id;

                if ($quiz->quiz_template_id){
                    ReportCorrectAnswerRate::query()
                        ->updateOrCreate([
                            'quiz_template_id' => $quiz->quiz_template_id,
                            'question_id' => $ranrom->id,
                        ],[
                            'question_type' => $ranrom->type,
                            'num_question_used' => (ReportCorrectAnswerRate::countQuestionUsed($quiz->quiz_template_id, $ranrom->id) + 1),
                        ]);
                }
            }
            else {
                $questions[$question->id] = [
                    'id' => $question->id,
                    'index' => $key,
                    'qindex' => $question->num_order,
                    'question_id' => $question->question_id,
                    'name' => $question->question->name,
                    'type' => $question->question->type,
                    'category_id' => $question->question->category_id,
                    'qqcategory_id' => $question->qqcategory,
                    'multiple' => $question->question->multiple,
                    'score_group' => $score_group,
                    'max_score' => $question->max_score,
                    'answers' => $this->getAnwsersQuestion($question->question_id),
                    'answer_horizontal' => $question->question->answer_horizontal,
                ];

                if ($quiz->quiz_template_id){
                    ReportCorrectAnswerRate::query()
                        ->updateOrCreate([
                            'quiz_template_id' => $quiz->quiz_template_id,
                            'question_id' => $question->question_id,
                        ],[
                            'question_type' => $question->question->type,
                            'num_question_used' => (ReportCorrectAnswerRate::countQuestionUsed($quiz->quiz_template_id, $question->question_id) + 1),
                        ]);
                }
            }
        }

        $this->template['questions'] = $questions;
    }

    /**
     * Get anwsers by quiz question
     * @param int $question_id
     * @return array
     * */
    protected function getAnwsersQuestion($question_id) {
        $question = Question::find($question_id);
        $anwsers = QuestionAnswer::whereQuestionId($question_id);
        if ($this->quiz->shuffle_answers == 1 && $question->shuffle_answers == 1){
            $anwsers->inRandomOrder();
        }
        $anwsers = $anwsers->get([
            'id',
            'title',
            'feedback_answer',
            'matching_answer',
            'percent_answer',
            'image_answer',
            'fill_in_correct_answer',
        ])->toArray();

        foreach ($anwsers as $index => $anwser) {
            if (strpos($anwser['title'], '<p>') !== false) {
                $title = str_replace(['<p>','</p>'], '', $anwser['title']);
                $anwsers[$index]['title'] = $title;
            }
            $anwsers[$index]['index_text'] = @$this->answer_text[$index];
            $anwsers[$index]['image_answer'] = $anwser['image_answer'] ? image_file($anwser['image_answer']) : '';
        }

        return $anwsers;
    }
}
