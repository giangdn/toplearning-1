<?php

namespace Modules\Suggest\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Suggest extends BaseModel
{
    protected $table = 'el_suggest';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'name',
        'content',
    ];
    public static function getAttributeName() {
        return [
            'user_id' => 'Nhân viên',
            'name' => 'Tên đề xuất',
            'content' => 'Nội dung đề xuất',
        ];
    }
}
