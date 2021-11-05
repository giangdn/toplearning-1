<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingQuestionAnswer2 extends Model
{
    protected $table = 'el_rating_question_answer2';
    protected $fillable = [
        'course_rating_level_id',
        'course_rating_level_object_id',
        'course_id',
        'course_type',
        'code',
        'name',
        'question_id',
        'is_text',
        'is_row',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Câu trả lời',
            'question_id' => 'Câu hỏi',
        ];
    }
}
