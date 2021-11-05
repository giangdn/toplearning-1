<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\ReportNew\Entities\BC26;
use Modules\ReportNew\Entities\ReportNewExportBC11;

class BC26Controller extends ReportNewController
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
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $user_id = $request->user_id;

        if (!$from_date && !$to_date)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC26::sql($from_date, $to_date, $user_id);
        $count = $query->count();
        $query->orderBy('user_code', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $cost_lecturer = ReportNewExportBC11::query()
                ->where('user_id', '=', $row->user_id)
                ->where('course_id', '=', $row->course_id)
                ->where('course_type', '=', $row->course_type)
                ->sum('cost_lecturer');
            $row->cost_lecturer = $cost_lecturer;

            $cost_tuteurs = ReportNewExportBC11::query()
                ->where('user_id', '=', $row->user_id)
                ->where('course_id', '=', $row->course_id)
                ->where('course_type', '=', $row->course_type)
                ->sum('cost_tuteurs');
            $row->cost_tuteurs = $cost_tuteurs;

            $unit = Unit::whereCode($row->unit_code_1)->first();
            $area = Area::find(@$unit->area_id);

            $row->area_name_unit = @$area->name;

            $course_time = '';
            $course_time_unit_text = '';

            if ($row->course_type == 2){
                $course = OfflineCourse::find($row->course_id);
                $course_time = $course->course_time;
                $course_time_unit = preg_replace("/[^a-z]/", '', $course->course_time_unit);

                switch ($course_time_unit){
                    case 'day': $course_time_unit_text = 'Ngày'; break;
                    case 'session': $course_time_unit_text = 'Buổi'; break;
                    case 'hour': $course_time_unit_text = 'Giờ'; break;
                }
            }
            $row->course_time = $course_time . ' ' . $course_time_unit_text;

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->cost = number_format($row->cost_lecturer + ($row->cost_tuteurs ? $row->cost_tuteurs : 0), 2);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
