<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingAnswerMatrix extends Model
{
    protected $table = 'el_rating_question_answer_matrix';
    protected $fillable = [
        'code',
        'question_id',
        'answer_row_id',
        'answer_col_id',
    ];
    protected $primaryKey = 'id';
}
