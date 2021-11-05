<?php

namespace Modules\TrainingUnit\Entities;

use Illuminate\Database\Eloquent\Model;

class ProposedQuestion extends Model
{
    protected $table = 'el_proposed_question';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'type',
        'category_id',
        'multiple',
        'created_by',
        'updated_by',
    ];

    public static function getAttributeName() {
        return [
            'name' => 'Tên câu hỏi',
            'type' => 'Loại',
            'category_id' => 'Danh mục',
            'multiple' => 'Chọn nhiều',
            'created_by' => trans('lageneral.creator'),
            'updated_by' => trans('lageneral.editor'),
        ];
    }
}
