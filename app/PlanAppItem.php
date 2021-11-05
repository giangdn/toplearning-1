<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PlanAppItem
 *
 * @property int $id
 * @property string $name
 * @property string|null $criteria_1
 * @property string|null $criteria_2
 * @property string|null $criteria_3
 * @property string|null $result
 * @property string|null $finish
 * @property int|null $sort
 * @property int $user_id
 * @property int $cate_id
 * @property int $plan_app_id
 * @property int $course_id
 * @property int $course_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereCateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereCriteria1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereCriteria2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereCriteria3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem wherePlanAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanAppItem whereUserId($value)
 * @mixin \Eloquent
 */
class PlanAppItem extends Model
{
    protected $table = 'el_plan_app_item';
    protected $fillable = [
        'name',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên mục tiêu',
            'criteria_1' => 'Tiêu chí 1',
            'criteria_2' => 'Tiêu chí 2',
            'criteria_3' => 'Tiêu chí 3',
            'result' => 'Kết quả đạt được',
            'finish' => 'tỷ lệ hoàn thành',
            'sort' => 'thứ tự',
            'user_id' => 'Mã user_id',
            'cate_id' => 'Mã nhóm đề mục',
            'plan_app_id' => 'Mã template',
            'course_id' => 'Mã khóa học',
            'course_type' => 'Loại khóa học'
        ];
    }
}
