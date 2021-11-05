<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class CapabilitiesConventionPercent extends Model
{
    protected $table = 'el_capabilities_convention_percent';
    protected $fillable = [
        'percent_id',
        'name',
    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'percent_id' => 'Nhóm phần trăm',
            'name' => 'Tên quy ước tỷ lệ',
        ];
    }

    public static function getConventPercent($percent){
        $query = self::query();
        return $query->select(['a.id','a.name'])
            ->from('el_capabilities_convention_percent AS a')
            ->leftJoin('el_capabilities_group_percent AS b', 'b.id', '=', 'a.percent_id')
            ->where('b.from_percent', '<=', $percent)
            ->where(function ($subquery) use ($percent) {
                $subquery->orWhere('b.to_percent', '>=', $percent);
                $subquery->orWhereNull('b.to_percent');
            })
            ->get();
    }
}
