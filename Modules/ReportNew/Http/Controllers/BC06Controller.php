<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC06;
use App\Models\Categories\TrainingPartner;

class BC06Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
            'level_name' => $level_name
        ]);
    }

    public function getData(Request $request)
    {
        $units = Unit::where('status', '=', 1)->get();
        $training_partners = TrainingPartner::get();

        $joined = $request->joined;
        $subject_id = $request->subject_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $training_type_id = $request->training_type_id;
        $title_id = $request->title_id;
        $unit_id = $request->unit_id;
        $area_id = $request->area;
        if (!$unit_id && !$subject_id)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC06::sql($joined, $subject_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id, $area_id);
        $count = $query->count();
        $query->orderBy('el_report_new_export_bc05.'.$sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            /*$row->course_code = $row->score ? $row->course_code : '';
            $row->course_name = $row->score ? $row->course_name : '';*/
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->joined = $row->result == 1 ? 'Tham gia' : 'Chưa tham gia';
            $row->result = $row->result == 1 ? 'Đạt' : 'Không đạt';
            switch ($row->status_user) {
                case 0:
                    $row->status_user = trans('backend.inactivity'); break;
                case 1:
                    $row->status_user = trans('backend.doing'); break;
                case 2:
                    $row->status_user = trans('backend.probationary'); break;
                case 3:
                    $row->status_user = trans('backend.pause'); break;
            }
            if ($row->course_type == 2){
                $offline = OfflineCourse::find($row->course_id);
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

                $register = OfflineRegister::whereCourseId($row->course_id)->where('user_id', '=', $row->user_id)->first();
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
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
