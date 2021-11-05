<?php

namespace Modules\Quiz\Entities;

use App\BaseModel;
use App\Permission;
use App\Scopes\DraftScope;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuestionCategory
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property int $status
 * @property int|null $unit_id
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class QuestionCategory extends BaseModel
{
    use ChangeLogs;
    protected $table = 'el_question_category';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'parent_id'];

    public static function getAttributeName() {
        return [
            'name' => 'Tên danh mục',
        ];
    }

    public static function getCategories($parent = null, $manager_ids = [], $exclude = null, $prefix = '', &$result = []) {

        $query = self::query();
        $query->where('parent_id', '=', $parent);
        $query->where('status', '=', 1);
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
        $query = Question::query();
        $query->where('category_id', '=', $cat_id);
        $query->where('status', '=', 1);
        return $query->count('id');
    }

    public static function getCategoryUnit($units = []) {
        $query = QuestionCategory::query();
        $query->where('status', '=', 1);
//        $query->whereIn('id', function ($subquery) use ($units) {
//            $subquery->select(['qcl_id'])
//                ->from('el_proposed_question_category_lib AS a')
//                ->join('el_proposed_question_category AS b', 'b.id', '=', 'a.pqc_id')
//                ->whereIn('unit_id', $units);
//        });
        return $query->pluck('id')->toArray();
    }
}
