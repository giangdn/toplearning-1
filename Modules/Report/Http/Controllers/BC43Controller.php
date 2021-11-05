<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRegister;
use Modules\Report\Entities\BC43;
use Modules\TrainingPlan\Entities\TrainingPlan;

class BC43Controller extends ReportController
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
        if (!$request->course_type && !$request->course )
            json_result([]);

        $course_type = $request->course_type;
        $course_id = $request->course;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC43::sql($course_id, $course_type);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
//dd($rows);
        foreach ($rows as $row){
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->reason_reality_manager = $row->reality_manager==2?$row->reason_reality_manager:'';
            $row->reality_manager = $row->reality_manager==1?'X':'';
            $row->result= $row->result==1?'Đạt':'Không đạt';
            $row->full_name = $row->lastname.' '.$row->firstname;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
