<?php

namespace Modules\CourseEducatePlan\Entities;

use Illuminate\Database\Eloquent\Model;

class CourseEducatePlanCost extends Model
{
    protected $table = "el_course_educate_plan_cost";
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
            'course_id' =>trans('lacourse.course'),
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
        $course_costs =
            CourseEducatePlanCost::where('course_id', '=', $id)->get();
        foreach($course_costs as $item){
            $total += $item->actual_amount;
        }

        return $total;
    }

    public static function getTotalPlanAmount($id)
    {
        $total = 0;
        $course_costs = CourseEducatePlanCost::where('course_id', '=', $id)->get();
        foreach($course_costs as $item){
            $total += $item->plan_amount;
        }
        return $total;
    }
}
