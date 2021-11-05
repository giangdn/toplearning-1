<?php

namespace Modules\Suggest\Entities;

use Illuminate\Database\Eloquent\Model;

class SuggestComment extends Model
{
    protected $table = 'el_suggest_comment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'suggest_id',
        'user_id',
        'content'
    ];

    public static function getAttributeName() {
        return [
            'user_id' => 'Nhân viên',
            'suggest_id' => 'Tên đề xuất',
            'content' => 'Nội dung bình luận',
        ];
    }
}
