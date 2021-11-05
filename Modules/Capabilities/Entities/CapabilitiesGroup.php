<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class CapabilitiesGroup extends Model
{
    protected $table = 'el_capabilities_group';
    protected $fillable = [
        'name',
        'basic_knowledge',
        'medium_knowledge',
        'advanced_knowledge',
        'profession_knowledge',
        'basic_skills',
        'medium_skills',
        'advanced_skills',
        'profession_skills',
        'basic_expression',
        'medium_expression',
        'advanced_expression',
        'profession_expression',

    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên nhóm',
            'basic_knowledge' => 'Kiến thức cơ bản',
            'medium_knowledge' => 'Kiến thức trung bình',
            'advanced_knowledge' => 'Kiến thức nâng cao',
            'profession_knowledge' => 'kiến thức chuyên nghiệp',
            'basic_skills' => 'Kĩ năng cơ bản',
            'medium_skills' => 'Kĩ năng trung bình',
            'advanced_skills' => 'Kĩ năng nâng cao',
            'profession_skills' => 'Kĩ nâng chuyên nghiệp',
            'basic_expression' => 'Biểu hiện cơ bản',
            'medium_expression' => 'Biểu hiện trung bình',
            'advanced_expression' => 'Biểu hiện nâng cao',
            'profession_expression' => 'Biểu hiện chuyên nghiệp',
        ];
    }

    public static function getByTitle($title_id) {
        $query = self::query();
        return $query->select(['c.id', 'c.name'])
            ->from('el_capabilities_title AS a')
            ->join('el_capabilities AS b', 'b.id', '=', 'a.capabilities_id')
            //->join('el_capabilities_group AS c', 'c.id', '=', 'b.group_id')
            ->join('el_capabilities_category AS c', 'c.id', '=', 'b.category_id')
            ->where('a.title_id', '=', $title_id)
            ->groupBy(['c.id', 'c.name'])
            ->get();
    }
}
