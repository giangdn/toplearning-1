<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Categories\CourseCategories
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property int $type
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Course whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Course whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\Course whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Course extends Model
{
    protected $table = 'el_course_categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'parent_id',
        'status',
        'type',
    ];

    public static function getCourseCategoriesParent($exclude_id = 0, $parent_id = null, $type = 0, $prefix = '', &$result = []) {
        $query = self::query();
        $query->where('parent_id', '=', $parent_id);
        if ($type) {
            $query->where('type', '=', $type);
        }
        $rows = $query->get();

        foreach ($rows as $row) {
            if ($row->id == $exclude_id) continue;
            $result[] = ['id' => $row->id, 'name' => $prefix.' '. $row->name];

            self::getCourseCategoriesParent($exclude_id, $row->id, $type, $prefix.'--', $result);
        }

        return $result;
    }

    public static function getAttributeName() {
        return [
            'name' => 'Tên khóa học',
            'status' => 'Trạng thái',
            'parent_id' => 'Cấp cha',
            'type' => 'Loại khóa học'
        ];
    }
}
