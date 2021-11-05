<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class CapabilitiesCategoryGroup extends Model
{
    protected $table = 'el_capabilities_category_group';
    protected $fillable = [
        'name',
        'category_id'
    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên danh mục',
            'category_id' => 'Danh mục cha'
        ];
    }
}
