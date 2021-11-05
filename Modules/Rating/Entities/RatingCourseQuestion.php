<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingCourseQuestion extends Model
{
    protected $table = 'el_rating_course_question';
    protected $fillable = [
        'course_category_id',
        'question_id',
        'question_name',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_category_id' => 'Danh mục đánh giá',
            'question_id' => 'Câu hỏi',
            'question_name' => 'Tên câu hỏi',
        ];
    }

    public function answers()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingCourseAnswer', 'course_question_id');
    }
}
