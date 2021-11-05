<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC18;

class BC18Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $area_id = $request->area_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $title_id = $request->title_id;
        $training_type_id = $request->training_type_id;
        $unit_id = $request->unit_id;


        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC18::sql($title_id,$unit_id,$area_id,$training_type_id,$from_date, $to_date);
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
//        $subjects = TrainingRoadmap::where(['title_id'=>$title_id])->get('id');
        foreach ($rows as $row){
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

            $offline = OfflineCourse::find($row->course_id);
            $row->course_time = @$offline->course_time;

            $row->start_date_format = get_date($row->start_date);
            $row->to_date_format = get_date($row->end_date);
            $row->time_commit_formatter = get_date($row->from_time_commit).' - '.get_date($row->to_time_commit);

            $row->cost_held = number_format($row->cost_held, 2);
            $row->cost_training = number_format($row->cost_training, 2);
            $row->cost_external = number_format($row->cost_external, 2);
            $row->cost_teacher = number_format($row->cost_teacher, 2);
            $row->cost_student = number_format($row->cost_student, 2);
            $row->cost_total = number_format($row->cost_total, 2);
            $row->cost_refund = number_format($row->cost_refund, 2);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
