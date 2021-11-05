<?php

namespace Modules\Survey\Entities;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswerMatrix extends Model
{
    protected $table = 'el_survey_template_question_answer_matrix';
    protected $fillable = [
        'code',
        'question_id',
        'answer_row_id',
        'answer_col_id',
    ];
    protected $primaryKey = 'id';
}
