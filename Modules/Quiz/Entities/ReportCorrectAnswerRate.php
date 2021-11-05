<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

class ReportCorrectAnswerRate extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'el_report_correct_answer_rate';
    protected $fillable = [
        'quiz_template_id',
        'question_id',
        'question_type',
        'num_question_used',
        'num_correct_answer',
        'num_answer',
    ];

    public static function countQuestionUsed($template_id, $question_id){
        $query = self::query()
            ->where('quiz_template_id', '=', $template_id)
            ->where('question_id', '=', $question_id)
            ->count();

        return $query;
    }
}
