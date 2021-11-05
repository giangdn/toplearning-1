<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswerMatrix2 extends Model
{
    protected $table = 'el_survey_template2_question_answer_matrix';
    protected $fillable = [
        'code',
        'question_id',
        'answer_row_id',
        'answer_col_id',
        'survey_id',
    ];
    protected $primaryKey = 'id';
}
