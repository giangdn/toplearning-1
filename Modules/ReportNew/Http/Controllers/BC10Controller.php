<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Discipline;
use App\Models\Categories\Unit;
use App\ProfileView;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC10;

class BC10Controller extends ReportNewController
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
            'level_name' => $level_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $subject_id = $request->subject_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $training_type_id = $request->training_type_id;
        $title_id = $request->title_id;
        $unit_id = $request->unit_id;
        $area_id = $request->area;
        if (!$from_date && !$to_date)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC10::sql($subject_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id, $area_id);
        $count = $query->count();
        $query->orderBy('el_offline_register_view.'.$sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $profile = ProfileView::whereUserId($row->user_id)->first();
            $course = OfflineCourse::find($row->course_id);

            $row->user_code = $profile->code;
            $row->full_name = $profile->full_name;
            $row->email = $profile->email;
            $row->phone = $profile->phone;
            $row->unit_name_1 = $profile->unit_name;
            $row->unit_name_2 = $profile->parent_unit_name;
            $row->position_name = $profile->position_name;
            $row->course_code = $course->code;
            $row->course_name = $course->name;
            $row->course_time = $course->course_time;
            $row->start_date = get_date($course->start_date);
            $row->end_date = get_date($course->end_date);

            $schedules = OfflineSchedule::query()
                ->select([
                    'a.end_time',
                    'a.lesson_date',
                    'b.absent_id',
                    'b.absent_reason_id',
                    'b.discipline_id',
                ])
                ->from('el_offline_schedule as a')
                ->leftJoin('el_offline_attendance as b', 'b.schedule_id', '=', 'a.id')
                ->where('a.course_id', '=', $row->course_id)
                ->where('b.register_id', '=', $row->id)
                ->get();
            foreach ($schedules as $schedule){
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                    $row->time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $row->time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }

                if ($schedule->absent_id != 0 || $schedule->absent_reason_id != 0 || $schedule->discipline_id != 0){
                    if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                        $row->schedule_discipline .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                    }else{
                        $row->schedule_discipline .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                    }

                    $discipline = Discipline::find($schedule->discipline_id);
                    $absent = Absent::find($schedule->absent_id);
                    $absent_reason = AbsentReason::find($schedule->absent_reason_id);
                    $row->discipline = $discipline ? $discipline->name.'; ' : '';
                    $row->absent = $absent ? $absent->name.'; ' : '';
                    $row->absent_reason = $absent_reason ? $absent_reason->name.'; ' : '';
                }
            }

            $row->attendance = $schedules->count();
            $row->result = 'Không đạt';

            switch ($profile->status_id) {
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
