<?php

namespace Modules\Quiz\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuestionCategoryUser
 *
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategoryUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategoryUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategoryUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategoryUser whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategoryUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategoryUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategoryUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategoryUser whereUserId($value)
 * @mixin \Eloquent
 * @property int $unit_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategoryUser whereUnitId($value)
 */
class QuestionCategoryUser extends Model
{
    protected $table = 'el_question_category_user';
    protected $fillable = [
        'unit_id',
        'category_id',
    ];

    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'unit_id' => 'Nhân viên',
            'category_id' => 'Danh mục câu hỏi',
        ];
    }

    public static function checkExists($category_id, $unit_id){
        $query = self::query();
        $query->where('category_id', '=', $category_id);
        $query->where('unit_id', '=', $unit_id);
        return $query->exists();
    }

    public static function getCategoryByUser($user_code, &$result = []) {
        $cate_id = QuestionCategoryUser::join('el_unit AS b', 'b.id', '=', 'unit_id')
            ->join('el_unit_manager AS c', 'c.unit_code', '=', 'b.code')
            ->where('c.user_code', '=', $user_code)
            ->pluck('category_id')->toArray();

        $rows = QuestionCategory::whereIn('id', $cate_id)->get();

        foreach ($rows as $row) {
            $result[] = $row->id;
            self::getCategoryChild($row->id, $result);
        }

        return $result;
    }

    public static function getCategoryChild($parent_id, &$result = []){
        $rows = QuestionCategory::where('parent_id', '=', $parent_id)->get();

        foreach ($rows as $row){
            $result[] = $row->id;
            self::getCategoryChild($row->id, $result);
        }

        return $result;
    }
}
