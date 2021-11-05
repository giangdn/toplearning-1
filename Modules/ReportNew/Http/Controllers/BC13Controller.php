<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\AbsentReason;
use App\Models\Categories\Area;
use App\Models\Categories\StudentCost;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\ReportNew\Entities\BC12;
use Modules\ReportNew\Entities\BC13;

class BC13Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $student_cost = StudentCost::whereStatus(1)->get();
        $traing_cost = TrainingCost::query()->get();

        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'level_name' => $level_name,
            'student_cost' => $student_cost,
            'traing_cost' => $traing_cost,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $unit_id = $request->unit_id;
        $area_id = $request->area_id;

        if (!$month && !$area_id)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC13::sql($year, $unit_id, $area_id);
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        $training_cost = TrainingCost::query()->pluck('id')->toArray();
        $student_cost = StudentCost::whereStatus(1)->pluck('id')->toArray();

        foreach ($rows as $row){
            $unit = Unit::find($row->unit_id_1);
            $area = Area::find(@$unit->area_id);
            $unit_type = UnitType::find(@$unit->type);

            $row->unit_name_1 = $row->unit_name_1 .' ('. $row->unit_code_1 .')';
            $row->unit_name_2 = $row->unit_name_2 .' ('. $row->unit_code_2 .')';
            $row->area_name = @$area->name;
            $row->unit_type = @$unit_type->name;

            $empl = 0;
            for ($i = 1; $i <= $month; $i++){
                $empl += $row->{'t'.$i};
            }
            $avg_user_by_year = $empl/$month;

            $total_cost = 0;

            $student = json_decode($row->total_academy_cost, true);
            foreach ($student_cost as $student_id){
                $offline_student_cost = isset($student['student_cost'.$student_id]) ? $student['student_cost'.$student_id] : 0;
                $total_cost += $offline_student_cost;

                $row->{'student_cost'.$student_id} = number_format($offline_student_cost, 2);
            }

            $course_cost = json_decode($row->total_organizational_cost, true);
            foreach ($training_cost as $cost_id){
                $course_cost_by_training_cost = isset($course_cost['traing_cost'.$cost_id]) ? $course_cost['traing_cost'.$cost_id] : 0;
                $total_cost += $course_cost_by_training_cost;

                $row->{'traing_cost'.$cost_id} = number_format($course_cost_by_training_cost, 2);
            }

            $row->avg_user_by_year = number_format($avg_user_by_year, 2);

            $row->avg_cost_user = number_format($total_cost/($row->avg_user_by_year > 0 ? $row->avg_user_by_year : 1), 2);
            $row->avg_cost_actual_number_participants = number_format($total_cost / ($row->actual_number_participants > 0 ? $row->actual_number_participants : 1), 2);
            $row->avg_cost_hits_actual_participation = number_format($total_cost / ($row->hits_actual_participation > 0 ? $row->hits_actual_participation : 1), 2);

            $row->actual_number_participants = number_format($row->actual_number_participants,2);
            $row->hits_actual_participation = number_format($row->hits_actual_participation, 2);
            $row->total_cost = number_format($total_cost, 2);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
