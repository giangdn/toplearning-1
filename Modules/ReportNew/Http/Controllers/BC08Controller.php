<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\StudentCost;
use App\Models\Categories\TrainingCost;
use App\TypeCost;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\ReportNew\Entities\BC08;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\Unit;
use Modules\Offline\Entities\OfflineCourse;

class BC08Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $type_cost = TypeCost::query()
            ->whereExists(function ($sub){
                $sub->select(['id'])
                    ->from('el_training_cost')
                    ->whereColumn('type', '=', 'el_type_cost.id');
            })
            ->orderBy('id')
            ->get(['id', 'name']);

        $count_training_cost = function ($type_cost_id){
            return TrainingCost::where('type', '=', $type_cost_id)->count();
        };

        $training_cost = TrainingCost::query()->orderBy('type')->orderBy('id')->get();
        $student_cost = StudentCost::whereStatus(1)->get();

        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'type_cost' => $type_cost,
            'training_cost' => $training_cost,
            'count_training_cost' => $count_training_cost,
            'student_cost' => $student_cost,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $units = Unit::where('status', '=', 1)->get();
        $training_partners = TrainingPartner::get();

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $training_type_id = $request->training_type_id;
        $title_id = $request->title_id;

        if (!$from_date && !$to_date)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC08::sql($from_date, $to_date, $training_type_id, $title_id);
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        $training_cost = TrainingCost::query()->pluck('id')->toArray();
        $student_cost = StudentCost::whereStatus(1)->pluck('id')->toArray();

        foreach ($rows as $row){
            $offline = OfflineCourse::find($row->course_id);
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

            $schedules = OfflineSchedule::query()
                ->select(['a.end_time', 'a.lesson_date'])
                ->from('el_offline_schedule as a')
                ->where('a.course_id', '=', $row->course_id)
                ->get();

            foreach ($schedules as $schedule){
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                    $row->time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $row->time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }
            }

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->recruits = $row->recruits == 1 ? 'X' : '';
            $row->exist = $row->exist == 1 ? 'X' : '';
            $row->plan = $row->plan == 1 ? 'X' : '';
            $row->incurred = $row->incurred == 1 ? 'X' : '';

            $course_cost = json_decode($row->course_cost, true);
            foreach ($training_cost as $cost_id){
                $row->{'cost_'.$cost_id} = isset($course_cost['cost_'.$cost_id]) ? $course_cost['cost_'.$cost_id] : 0;
            }

            $student = json_decode($row->student_cost, true);
            foreach ($student_cost as $student_id){
                $row->{'student'.$student_id} = isset($student['student'.$student_id]) ? $student['student'.$student_id] : 0;
            }
            $row->student_total = $student['student_cost_total'];

            $teacher_account_number = OfflineTeacher::query()
                ->from('el_offline_course_teachers AS a')
                ->join('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id')
                ->where('a.course_id', '=', $row->course_id)
                ->whereIn('a.teacher_id', function ($sub) use ($row){
                    $sub->select(['teacher_main_id'])
                        ->from('el_offline_schedule')
                        ->where('course_id', '=', $row->course_id)
                        ->pluck('teacher_main_id')
                        ->toArray();
                })
                ->pluck('b.account_number')
                ->toArray();
            $row->teacher_account_number = implode('; ', $teacher_account_number);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
