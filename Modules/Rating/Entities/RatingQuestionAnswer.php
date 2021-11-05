<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingQuestionAnswer extends Model
{
    protected $table = 'el_rating_question_answer';
    protected $fillable = [
        'name',
        'question_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Câu trả lời',
            'question_id' => 'Câu hỏi',
        ];
    }

    public static function getAnswer($question_id)
    {
        $query = self::query();
        return $query->select(['id', 'name', 'is_text'])
            ->where('question_id', '=', $question_id)
            ->get();
    }
}
