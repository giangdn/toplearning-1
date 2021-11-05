<?php

namespace Modules\Rating\Entities;

use Illuminate\Database\Eloquent\Model;

class RatingQuestion extends Model
{
    protected $table = 'el_rating_question';
    protected $fillable = [
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

    public static function getQuestion($category_id)
    {
        $query = self::query();
        return $query->select(['id', 'name', 'type', 'multiple'])
            ->where('category_id', '=', $category_id)
            ->get();
    }

    public function answers()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingQuestionAnswer','question_id');
    }

    public function answers_matrix()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingAnswerMatrix','question_id');
    }
}
