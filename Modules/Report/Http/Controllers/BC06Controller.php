<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\TrainingTeacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Report\Entities\BC06;

class BC06Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->from_date && !$request->to_date && !$request->teacher_type)
            json_result([]);

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $teacher_type = $request->teacher_type;
        $teacher = $request->teacher;

        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC06::sql($from_date, $to_date, $teacher, $teacher_type);
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $key => $row) {
            $num_lesson_date = OfflineSchedule::where('course_id', '=', $row->course_id)
                ->where('teacher_main_id', '=', $row->id)
                ->groupBy(['lesson_date'])
                ->count();

            $row->num_lesson_date = $num_lesson_date;
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
