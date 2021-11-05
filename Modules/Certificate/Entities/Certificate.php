<?php

namespace Modules\Certificate\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Certificate extends BaseModel
{
    protected $table = "el_certificate";
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'image',
    ];

    public static function getAttributeName() {
        return [
            'code' => 'Mã chứng chỉ',
            'name' => 'Tên chứng chỉ',
            'image' => 'Ảnh chứng chỉ',
        ];
    }
}
