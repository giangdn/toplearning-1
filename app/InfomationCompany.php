<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfomationCompany extends Model
{
    protected $table = 'el_infomation_company';
    protected $primaryKey = 'id';
    protected $fillable = [
        'content',
        'title',
        'created_by',
        'updated_by',
    ];

    public static function getAttributeName() {
        return [
            'content' => 'Nội dung',
            'title' => 'Tiêu đề',
        ];
    }
}
