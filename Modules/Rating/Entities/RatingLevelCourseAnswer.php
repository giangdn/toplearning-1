<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingLevelCourseAnswer extends Model
{
    protected $table = 'el_rating_level_course_answer';
    protected $fillable = [
        'course_question_id',
        'answer_id',
        'answer_name',
        'text_answer',
        'check_answer_matrix',
    ];
    protected $primaryKey = 'id';
}
