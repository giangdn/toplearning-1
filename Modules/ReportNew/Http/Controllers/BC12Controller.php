<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\AbsentReason;
use App\Models\Categories\Area;
use App\Models\Categories\StudentCost;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\ProfileView;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\ReportNew\Entities\BC12;
use App\Models\Categories\TrainingPartner;

class BC12Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $student_cost = StudentCost::whereStatus(1)->get();

        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'level_name' => $level_name,
            'student_cost' => $student_cost,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $units = Unit::where('status', '=', 1)->get();
        $training_partners = TrainingPartner::get();

        $training_area_id = $request->training_area_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $training_type_id = $request->training_type_id;
        $title_id = $request->title_id;
        $unit_id = $request->unit_id;

        if (!$from_date && !$to_date)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC12::sql($training_area_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id);
        $count = $query->count();
        $query->orderBy('el_course_view.'.$sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $profile = ProfileView::whereUserId(@$row->user_id)->first();
            $unit = Unit::find($profile->unit_id);
            $area = Area::find(@$unit->area_id);
            $unit_type = UnitType::find(@$unit->type);
            $offline = OfflineCourse::find($row->course_id);
            $offline_result = OfflineResult::whereCourseId($row->course_id)->whereUserId($row->user_id)->first();

            $row->user_code = $profile->code;
            $row->fullname = $profile->full_name;
            $row->email = $profile->email;
            $row->area_name_unit = @$area->name;
            $row->phone = $profile->phone;
            $row->unit_name_1 = $profile->unit_name;
            $row->unit_name_2 = $profile->parent_unit_name;
            $row->unit_type_name = @$unit_type->anme;
            $row->position_name = $profile->position_name;
            $row->title_name = $profile->title_name;

            $row->course_code = $offline->code;
            $row->course_name = $offline->name;
            $row->start_date = get_date($offline->start_date);
            $row->end_date = get_date($offline->end_date);
            $row->course_time = $offline->course_time;

            $unit_name = [];
            !empty($offline->training_unit) ? $training_unit = json_decode($offline->training_unit) : $training_unit = [];
            if($offline->training_unit_type == 0 && !empty($training_unit)) {
                foreach ($units as $key => $unit) {
                    if(in_array($unit->id, $training_unit)) {
                        $unit_name[] = $unit->name;
                    }
                }
            } else if ($offline->training_unit_type == 1 && !empty($training_unit)) {
                foreach ($training_partners as $key => $training_partner) {
                    if(in_array($training_partner->id, $training_unit)) {
                        $unit_name[] = $training_partner->name;
                    }
                }
            }
            $row->training_unit = !empty($unit_name) ? implode(',',$unit_name) : '';
            $row->training_form_name = 'Đào tạo tập trung';

            $register = OfflineRegister::whereCourseId($row->course_id)->where('user_id', '=', $row->user_id)->first();
            $schedules = OfflineSchedule::query()
                ->select(['a.end_time', 'a.lesson_date', 'b.absent_reason_id'])
                ->from('el_offline_schedule as a')
                ->leftJoin('el_offline_attendance as b', 'b.schedule_id', '=', 'a.id')
                ->where('a.course_id', '=', $row->course_id)
                ->where('b.register_id', '=', $register->id)
                ->get();
            foreach ($schedules as $key => $schedule){
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                    $row->time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $row->time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }

                if ($schedule->absent_reason_id != 0){
                    $absent_reason = AbsentReason::find($schedule->absent_reason_id);
                    $row->note .= $absent_reason ? 'Buổi '.($key + 1).': '.$absent_reason->name.'; ' : '';
                }
            }
            $total_cost = 0;
            $student_cost = StudentCost::whereStatus(1)->get();
            foreach ($student_cost as $item){
                $offline_student_cost = OfflineStudentCost::whereRegisterId($register->id)
                    ->where('cost_id', '=', $item->id)
                    ->first();

                $row->{'student_cost'.$item->id} = number_format(@$offline_student_cost->cost, 2);

                $total_cost += @$offline_student_cost->cost;
            }

            $off_register = OfflineRegister::whereCourseId($row->course_id)->where('status', '=', 1)->count();
            $total_register = $off_register > 0 ? $off_register : 1;
            $teacher_cost = OfflineCourseCost::sumActualAmount($row->course_id, 4);
            $organizational_costs = OfflineCourseCost::sumActualAmount($row->course_id, 1);
            $academy_cost = OfflineCourseCost::sumActualAmount($row->course_id, 5);

            $avg_teacher_cost = ($teacher_cost/$total_register);
            $avg_organizational_costs = ($organizational_costs/$total_register);
            $avg_academy_cost = ($academy_cost/$total_register);

            $total_cost += ($avg_teacher_cost + $avg_organizational_costs + $avg_academy_cost);
            $row->total_cost = number_format($total_cost, 2);

            $row->avg_teacher_cost = number_format($teacher_cost/$total_register, 2);
            $row->avg_organizational_costs = number_format($organizational_costs/$total_register, 2);
            $row->avg_academy_cost = number_format($academy_cost/$total_register, 2);

            $row->result = $offline_result ? $offline_result->result == 1 ? 'Đạt' : 'Không đạt' : '_';
            $row->score = $offline_result ? $offline_result->score : '_';

            switch (@$profile->status_id) {
                case 0:
                    $row->status_user = trans('backend.inactivity'); break;
                case 1:
                    $row->status_user = trans('backend.doing'); break;
                case 2:
                    $row->status_user = trans('backend.probationary'); break;
                case 3:
                    $row->status_user = trans('backend.pause'); break;
            }

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
