<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Area;
use App\Models\Categories\Discipline;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\Unit;
use App\TypeCost;
use App\Profile;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC10;
use Modules\ReportNew\Entities\BC11;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use App\Models\Categories\TrainingTeacher;

class BC11Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $training_cost = TrainingCost::query()
        ->select('a.*')
        ->from('el_training_cost as a')
        ->leftjoin('el_type_cost as b','b.id','=','a.type')
        ->where(function($sub) {
            $sub->where('b.name','like','%giảng viên nội bộ%');
            $sub->orWhere('b.name','like','%giảng viên bên ngoài%');
        })
        ->orderBy('id')->get();

        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'training_cost' => $training_cost,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        if (!$from_date && !$to_date)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC11::sql($from_date, $to_date);
        $count = $query->get()->count();
        $query->orderBy('el_report_new_export_bc11.user_id', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        $training_type_cost_outside = TypeCost::where('name','like','%giảng viên bên ngoài%')->pluck('id')->toArray();
        $training_type_cost_inside = TypeCost::where('name','like','%giảng viên nội bộ%')->pluck('id')->toArray();

        foreach ($rows as $row){
            $training_teacher = TrainingTeacher::where('code',$row->user_code)->first();

            $time_lecturer = ReportNewExportBC11::query()
                ->where('training_teacher_id', '=', $row->training_teacher_id)
                ->where('user_id', '=', $row->user_id)
                ->where('course_id', '=', $row->course_id)
                ->where('course_type', '=', $row->course_type)
                ->where('subject_id', '=', $row->subject_id)
                ->sum('time_lecturer');
            $row->time_lecturer = $time_lecturer;

            $time_tuteurs = ReportNewExportBC11::query()
                ->where('training_teacher_id', '=', $row->training_teacher_id)
                ->where('user_id', '=', $row->user_id)
                ->where('course_id', '=', $row->course_id)
                ->where('course_type', '=', $row->course_type)
                ->sum('time_tuteurs');
            $row->time_tuteurs = $time_tuteurs;

            $unit = Unit::whereCode($row->unit_code_1)->first();
            $area = Area::find(@$unit->area_id);

            $row->area_name_unit = @$area->name;

            $offline = OfflineCourse::find($row->course_id);
            $row->course_time = $offline->course_time;

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);

            $schedules = OfflineSchedule::query()
                ->where('course_id', '=', $row->course_id)
                ->where(function ($sub) use ($row){
                    $sub->where('teacher_main_id', '=', $row->training_teacher_id);
                    $sub->orWhere('teach_id', '=', $row->training_teacher_id);
                })
                ->get();
            foreach ($schedules as $schedule){
                if ($schedule->end_time <= '13:00:00'){
                    $row->time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $row->time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }
            }

//            if ($row->role_lecturer == 1){
//                $cost = $row->cost_lecturer;
//                $row->cost = number_format($row->cost_lecturer, 2);
//            }
//            if ($row->role_tuteurs == 1){
//                $cost = $row->cost_tuteurs;
//                $row->cost = number_format($row->cost_tuteurs, 2);
//            }

            $row->role_lecturer = ($row->role_lecturer == 1) ? 'X' : '';
            $row->role_tuteurs = ($row->role_tuteurs == 1) ? 'X' : '';

            $total = 0;

            if($training_teacher->type == 1) {
                $training_cost = TrainingCost::query()->whereIn('type',$training_type_cost_inside)->orderBy('id')->get();
                foreach ($training_cost as $item){
                    $offline_cost = OfflineCourseCost::whereCourseId($row->course_id)->where('cost_id', '=', $item->id)->first();
                    $row->{'training_cost'.$item->id} = number_format(@$offline_cost->actual_amount, 2);

                    $total += @$offline_cost->actual_amount;
                }
            } else {
                $training_cost = TrainingCost::query()->whereIn('type',$training_type_cost_outside)->orderBy('id')->get();
                foreach ($training_cost as $item){
                    $offline_cost = OfflineCourseCost::whereCourseId($row->course_id)->where('cost_id', '=', $item->id)->first();
                    $row->{'training_cost'.$item->id} = number_format(@$offline_cost->actual_amount, 2);

                    $total += @$offline_cost->actual_amount;
                }
            }

            if($row->role_lecturer == 1) {
                $row->teacher = $row->teacher;
            }

            $row->total_cost = number_format($total, 2);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
