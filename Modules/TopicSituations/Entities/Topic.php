<?php

namespace Modules\TopicSituations\Entities;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;

class Topic extends BaseModel
{
    protected $table = 'el_topic';
    protected $fillable = [
        'name',
        'code',
        'image',
        'created_by',
        'updated_by',
        'unit_by',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên xử lý tình huống',
            'code' => 'Mã xử lý tình huống',
            'iamge' => 'ảnh',
        ];
    }
}
