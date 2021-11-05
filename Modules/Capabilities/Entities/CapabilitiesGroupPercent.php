<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class CapabilitiesGroupPercent extends Model
{
    protected $table = 'el_capabilities_group_percent';
    protected $fillable = [
        'to_percent',
        'from_percent',
        'percent_group',

    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'to_percent' => 'Đến phần trăm',
            'from_percent' => 'Từ phần trăm',
            'percent_group' => 'Nhóm phần trăm',
        ];
    }
}
