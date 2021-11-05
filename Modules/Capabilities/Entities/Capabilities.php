<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class Capabilities extends Model
{
    protected $table = 'el_capabilities';
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'category_group_id',
        'group_id',
        'description',
    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Kí hiệu',
            'name' => 'Tên năng lực',
            'category_id' => 'Danh mục chung',
            'category_group_id' => 'Danh mục nhóm',
            'group_id' => 'Nhóm năng lực',
            'description' => 'Diễn giải',
        ];
    }

    public static function getByTitleGroup($title_id, $category_id) {
        $query = self::query();
        return $query->select([
            'a.id',
            'a.number_title',
            'b.code',
            'b.name',
            'a.weight',
            'a.critical_level',
            'a.level',
            'a.goal',
            'b.id AS capabilities_id'
        ])
            ->from('el_capabilities_title AS a')
            ->join('el_capabilities AS b', 'b.id', '=', 'a.capabilities_id')
            ->where('title_id', '=', $title_id)
            ->where('category_id', '=', $category_id)
            ->orderBy('a.number_title', 'asc')
            ->get();
    }

    public static function getTotalWeightByTitleGroup($title_id, $category_id){
        $query = self::query();
        $query->select([
            'a.id',
            'a.number_title',
            'b.code',
            'b.name',
            'a.weight',
            'a.critical_level',
            'a.level',
            'a.goal',
            'b.id AS capabilities_id'
        ])
            ->from('el_capabilities_title AS a')
            ->join('el_capabilities AS b', 'b.id', '=', 'a.capabilities_id')
            ->where('title_id', '=', $title_id)
            ->where('category_id', '=', $category_id)
            ->orderBy('a.number_title', 'asc');

        $rows = $query->get();

        $total = 0;
        foreach ($rows as $item){
            $total += $item->weight;
        }

        return $total;
    }
}
