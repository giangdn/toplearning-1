<?php

namespace Modules\TrainingUnit\Entities;

use Illuminate\Database\Eloquent\Model;

class ProposedQuestionAnswer extends Model
{
    protected $table = 'el_proposed_question_answer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'question_id',
        'is_text',
        'correct_answer'
    ];

    public static function getAttributeName() {
        return [
            'title' => 'Tên câu trả lời',
            'question_id' => 'Câu hỏi',
            'is_text' => 'Nhập chữ',
            'correct_answer' => 'Đáp án đúng',
        ];
    }
}
