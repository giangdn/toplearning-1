<?php

namespace Modules\TrainingUnit\Entities;

use App\Permission;
use Illuminate\Database\Eloquent\Model;

class ProposedQuestionCategory extends Model
{
    protected $table = 'el_proposed_question_category';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'parent_id', 'unit_id'];

    public static function getAttributeName() {
        return [
            'name' => 'Tên danh mục',
        ];
    }

    public static function getCategories($parent = null, $manager_ids = [], $exclude = null, $prefix = '', &$result = []) {
        $query = self::query();
        $query->where('parent_id', '=', $parent);
        $query->where('id', '!=', $exclude);

        if (!Permission::isAdmin()) {
            $query->whereIn('id', $manager_ids);
        }

        $rows = $query->get();
        foreach ($rows as $row) {
            $result[] = (object) [
                'id' => $row->id,
                'name' => $prefix . $row->name
            ];

            self::getCategories($row->id, $manager_ids, $exclude, $prefix . '-- ',$result);
        }

        return $result;
    }

    public static function countQuestion($cat_id) {
        $query = ProposedQuestion::query();
        $query->where('category_id', '=', $cat_id);
        $query->where('status', '=', 1);
        return $query->count('id');
    }

    public static function getCategoryByUser($user_id = null) {
        $user_id = empty($user_id) ? \Auth::id() : $user_id;
        $ids = [];
        $query = ProposedQuestionCategory::query();
        $query->whereIn('unit_id', $ids);
        return $query->pluck('id')->toArray();
    }
}
