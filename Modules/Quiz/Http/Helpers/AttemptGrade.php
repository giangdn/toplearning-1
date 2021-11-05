<?php

namespace Modules\Quiz\Http\Helpers;

use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\ReportCorrectAnswerRate;

class AttemptGrade
{
    protected $attempt;

    /**
     * construct
     * @param \Modules\Quiz\Entities\QuizAttempts $attempt
     * */
    public function __construct($attempt)
    {
        $this->attempt = $attempt;
    }

    public function getGrade() {
        $template = $this->attempt->getTemplateData();
        $questions = $template['questions'];
        $grade = 0;
        foreach ($questions as $index => $question) {
            $grade += $this->gradeQuestion($question);
            $questions[$index] = $question;

            $report = ReportCorrectAnswerRate::query()
                ->where('quiz_template_id', '=', $this->attempt->quiz->quiz_template_id)
                ->where('question_id', '=', $question['question_id'])->first();
            if ($report){
                if ($question['score_group'] == $question['score']){
                    $report->num_correct_answer = $report->num_correct_answer + 1;
                }
                $report->num_answer = $report->num_answer + 1;
                $report->save();
            }
        }

        $template['questions'] = $questions;
        $this->attempt->updateTemplateData($template);

        return $grade;
    }

    protected function gradeQuestion(&$question) {
        $score = 0;
        if ($question['type'] == 'multiple-choise') {
            if (isset($question['answer'])){
                $answer_selected = $question['answer'];

                if ($question['multiple'] == 0){
                    $selected_answer = QuestionAnswer::whereIn('id', $answer_selected)
                        ->where('question_id', '=', $question['question_id'])
                        ->where('correct_answer', '=', 1)
                        ->count();

                    $score = ($question['score_group'] * $question['max_score']) * $selected_answer;

                    $question['correct_answers'] = QuestionAnswer::where('question_id', '=', $question['question_id'])
                        ->where('correct_answer', '=', 1)
                        ->pluck('id')
                        ->toArray();
                }

                if ($question['multiple'] == 1){
                    $count_answer = QuestionAnswer::where('question_id', '=', $question['question_id'])->count();
                    $correct_answer = QuestionAnswer::where('question_id', '=', $question['question_id'])->where('percent_answer', '>', 0)->count();
                    $selected = QuestionAnswer::where('question_id', '=', $question['question_id'])
                        ->whereIn('id', $answer_selected)->get();

                    if ($selected->count() == $count_answer && $correct_answer < $count_answer){
                        $score = 0;
                    }else{
                        $score = 0;
                        foreach ($selected as $item){
                            $score += (($question['score_group'] * $question['max_score']) * $item->percent_answer ) / 100;
                        }
                    }

                    $question['correct_answers'] = QuestionAnswer::where('question_id', '=', $question['question_id'])
                        ->where('percent_answer', '>', 0)
                        ->pluck('id')
                        ->toArray();
                }
            }else{
                $score = 0;
            }
        }
        if ($question['type'] == 'matching'){

            if (isset($question['matching'])){
                $matching_select = $question['matching'];
                $correct_answers = [];

                $answers = QuestionAnswer::where('question_id', '=', $question['question_id'])->get();
                $count = 0;
                foreach ($answers as $answer){
                    if ($matching_select[$answer->id] == $answer->matching_answer){
                        $count += 1;
                        $correct_answers[] = $answer->id;
                    }
                }
                if ($count == $answers->count()){
                    $score = ($question['score_group'] * $question['max_score']);
                }

                $question['correct_answers'] = $correct_answers;
            }
        }
        if ($question['type'] == 'fill_in_correct') {
            if (isset($question['text_essay'])){
                $fill_in_correct_selected = $question['text_essay'];

                $answers = QuestionAnswer::where('question_id', '=', $question['question_id'])->get();
                $count = 0;
                $percent = ($answers->count() > 0) ? 100/$answers->count() : 0;
                foreach ($answers as $key => $answer){
                    if (\Str::lower($answer->fill_in_correct_answer) == \Str::lower($fill_in_correct_selected[$key])){
                        $count += $percent;
                    }
                }

                $score = (($question['score_group'] * $question['max_score']) * $count) / 100;

            }else{
                $score = 0;
            }
        }
        if ($question['type'] == 'essay' || $question['type'] == 'fill_in'){
            if (isset($question['score']) && $question['score'] > 0){
                $score = $question['score'];
            }
        }

        $question['score'] = $score;

        return $score;
    }
}
