<?php

namespace Modules\Forum\Entities;

use Illuminate\Database\Eloquent\Model;

class FilterWord extends Model
{
    protected $table = 'el_filter_words';
    protected $fillable = [
        'name',
        'status',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Chữ',
            'status'=>'Trạng thái',
        ];
    }
}
