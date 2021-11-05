<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class CapabilitiesCategory extends Model
{
    protected $table = 'el_capabilities_category';
    protected $fillable = [
        'name',
    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên danh mục',
        ];
    }

}
