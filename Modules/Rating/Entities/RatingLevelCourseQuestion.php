<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingLevelCourseQuestion extends Model
{
    protected $table = 'el_rating_level_course_question';
    protected $fillable = [
        'course_category_id',
        'question_id',
        'question_name',
    ];
    protected $primaryKey = 'id';

    public function answers()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingLevelCourseAnswer', 'course_question_id');
    }

    public function answers_matrix()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingLevelCourseAnswerMatrix', 'course_question_id');
    }
}
