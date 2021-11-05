<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingLevelCourseAnswerMatrix extends Model
{
    protected $table = 'el_rating_level_course_answer_matrix';
    protected $fillable = [
        'course_question_id',
        'answer_code',
        'answer_row_id',
        'answer_col_id',
    ];
    protected $primaryKey = 'id';
}
