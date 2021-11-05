<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class CapabilitiesTitle extends Model
{
    protected $table = 'el_capabilities_title';
    protected $fillable = [
        'number_title',
        'capabilities_id',
        'title_id',
        'weight',
        'critical_level',
        'level',
        'goal',

    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'number_title' => 'Số thứ tự',
            'capabilities_id' => 'Khung năng lực',
            'title_id' => 'Chức danh',
            'weight' => 'Trọng số',
            'critical_level' => 'Mức độ quan trọng',
            'level' => 'Cấp độ',
            'goal' => 'Điểm chuẩn',
        ];
    }

    public static function checkNumber($title_id, $number, $id = null){
        $query = self::query();
        $query->where('title_id', '=', $title_id);
        $query->where('number_title', '=', $number);
        if ($id){
            $query->where('id', '!=', $id);
        }
        return $query->exists();
    }

    public static function checkExists($capabilities_id, $title_id, $id = null){
        $query = self::query();
        $query->where('capabilities_id', '=', $capabilities_id);
        $query->where('title_id', '=', $title_id);
        if ($id){
            $query->where('id', '!=', $id);
        }
        return $query->exists();
    }

    public static function getGoal($level, $critical_level, $weight) {
        return round((($level * $critical_level) * $weight / 100), 2);
    }

    public static function checkWeight($title_id, $id = null){
        $query = self::query();
        $query->where('title_id', '=', $title_id);
        if ($id){
            $query->where('id', '!=', $id);
        }
        $capabilities_titles = $query->get();

        $total_weight = 0;

        foreach ($capabilities_titles as $capabilities_title){
            $total_weight += $capabilities_title->weight;
        }

        return $total_weight;
    }


}
