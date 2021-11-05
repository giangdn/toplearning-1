<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingQuestion2 extends Model
{
    protected $table = 'el_rating_question2';
    protected $fillable = [
        'course_rating_level_id',
        'course_rating_level_object_id',
        'course_id',
        'course_type',
        'code',
        'name',
        'category_id',
        'type',
        'multiple',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Câu hỏi',
            'category_id' => 'Danh mục',
            'type' => 'Loại câu hỏi',
        ];
    }

    public function answers()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingQuestionAnswer2','question_id');
    }

    public function answers_matrix()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingAnswerMatrix2','question_id');
    }
}
