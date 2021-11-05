<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class CapabilitiesDictionary extends Model
{
    protected $table = 'el_capabilities_dictionary';
    protected $fillable = [
        'capabilities_id',
        'basic_apply',
        'medium_apply',
        'advanced_apply',
        'profession_apply',
        'basic_complex',
        'medium_complex',
        'advanced_complex',
        'profession_complex',
        'basic_affect',
        'medium_affect',
        'advanced_affect',
        'profession_affect',

    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'capabilities_id' => 'Tên nhóm',
            'basic_apply' => 'mức độ áp dụng cấp 1',
            'medium_apply' => 'mức độ áp dụng cấp 2',
            'advanced_apply' => 'mức độ áp dụng cấp 3',
            'profession_apply' => 'mức độ áp dụng cấp 4',

            'basic_complex' => 'mức độ phức tạp cấp 1',
            'medium_complex' => 'mức độ phức tạp cấp 2',
            'advanced_complex' => 'mức độ phức tạp cấp 3',
            'profession_complex' => 'mức độ phức tạp cấp 4',

            'basic_affect' => 'phạm vi ảnh hưởng cấp 1',
            'medium_affect' => 'phạm vi ảnh hưởng cấp 2',
            'advanced_affect' => 'phạm vi ảnh hưởng cấp 3',
            'profession_affect' => 'phạm vi ảnh hưởng cấp 4',
        ];
    }
}
