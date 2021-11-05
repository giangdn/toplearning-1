<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingCourseAnswer extends Model
{
    protected $table = 'el_rating_course_answer';
    protected $fillable = [
        'course_question_id',
        'answer_id',
        'answer_name',
        'text_answer',
        'check_answer_matrix',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_question_id' => 'Câu hỏi',
            'answer_id' => 'Câu trả lời',
            'answer_name' => 'Tên câu trả lời',
            'text_answer' => 'Nội dung câu trả lời',
        ];
    }
}
