<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeCost extends Model
{
    protected $table = 'el_type_cost';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
    ];

    public static function getAttributeName() {
        return [
            'name' => 'Tên loại chi phí',
            'code' => 'Mã loại chi phí',
        ];
    }
}
