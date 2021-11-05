<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingAnswerMatrix2 extends Model
{
    protected $table = 'el_rating_question_answer_matrix2';
    protected $fillable = [
        'course_rating_level_id',
        'course_rating_level_object_id',
        'course_id',
        'course_type',
        'code',
        'question_id',
        'answer_row_id',
        'answer_col_id',
    ];
    protected $primaryKey = 'id';
}
