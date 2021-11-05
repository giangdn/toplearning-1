<?php

namespace Modules\Online\Entities;

use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseCost
 *
 * @property int $course_id
 * @property int $cost_id
 * @property int|null $plan_amount
 * @property int|null $actual_amount
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCost whereActualAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCost whereCostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCost whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCost whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCost wherePlanAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseCost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OnlineCourseCost extends Model
{
    use ChangeLogs;

    protected $table = "el_online_course_cost";
    protected $fillable = [
        'course_id',
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

    public static function checkCostExists($course_id, $cost_id){
        $query = self::query();
        $query->where('cost_id', '=', $cost_id);
        $query->where('course_id', '=', $course_id);
        return $query->exists();
    }

    public static function getTotalActualAmount($id)
    {
        $total = 0;
        $course_costs = OnlineCourseCost::where('course_id', '=', $id)->get();
        foreach($course_costs as $item){
            $total += $item->actual_amount;
        }

        return $total;
    }

    public static function getTotalPlanAmount($id)
    {
        $total = 0;
        $course_costs = OnlineCourseCost::where('course_id', '=', $id)->get();
        foreach($course_costs as $item){
            $total += $item->plan_amount;
        }

        return $total;
    }
}
