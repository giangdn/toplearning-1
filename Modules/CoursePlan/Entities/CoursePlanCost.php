<?php

namespace Modules\CoursePlan\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\CoursePlan\Entities\CoursePlanCost
 *
 * @property int $id
 * @property int $course_id
 * @property int $course_type
 * @property int $cost_id
 * @property int|null $plan_amount
 * @property int|null $actual_amount
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost query()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost whereActualAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost whereCostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost wherePlanAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlanCost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CoursePlanCost extends Model
{
    protected $table = "el_course_plan_cost";
    protected $fillable = [
        'course_id',
        'course_type',
        'cost_id',
        'plan_amount',
        'actual_amount',
        'notes',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'cost_id' => 'Loại chi phí',
            'course_id' => 'Khóa học',
            'plan_amount'  => 'Tiền tạm tính',
            'actual_amount' => 'Tiền thực thi',
            'notes' => 'Ghi chú',
        ];
    }

    public static function checkCostExists($course_id, $course_type, $cost_id){
        $query = self::query();
        $query->where('cost_id', '=', $cost_id);
        $query->where('course_id', '=', $course_id);
        $query->where('course_type', '=', $course_type);
        return $query->exists();
    }

    public static function getTotalActualAmount($id, $course_type)
    {
        $total = 0;
        $course_costs = CoursePlanCost::where('course_id', '=', $id)->where('course_type', '=', $course_type)->get();
        foreach($course_costs as $item){
            $total += $item->actual_amount;
        }

        return $total;
    }

    public static function getTotalPlanAmount($id, $course_type)
    {
        $total = 0;
        $course_costs = CoursePlanCost::where('course_id', '=', $id)->where('course_type', '=', $course_type)->get();
        foreach($course_costs as $item){
            $total += $item->plan_amount;
        }

        return $total;
    }
}
