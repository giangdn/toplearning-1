<?php

namespace Modules\Report\Http\Controllers;

use App\PlanApp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineResult;
use Modules\Report\Entities\BC13;
use function PHPSTORM_META\elementType;

class BC13Controller extends ReportController
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
        if (!$request->from_date && !$request->to_date)
            json_result([]);

        $type = $request->type;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'course_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC13::sql($type, $from_date, $to_date);
        $count = $query->count();
//        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            if ($row->course_type == 1){
                $onl_result = OnlineResult::where('register_id', '=', $row->id)
                    ->where('course_id', '=', $row->course_id)
                    ->where('user_id', '=', $row->user_id)
                    ->first('result');
            }else{
                $off_result = OfflineResult::where('register_id', '=', $row->id)
                    ->where('course_id', '=', $row->course_id)
                    ->where('user_id', '=', $row->user_id)
                    ->first('result');
            }

            $plan_app = PlanApp::where('course_type', '=', $row->course_type)
                ->where('course_id', '=', $row->course_id)
                ->where('user_id', '=', $row->user_id)
                ->first();

            $row->full_name = $row->lastname. ' ' .$row->firstname;
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');

            $row->rating_course = $plan_app && $plan_app->status < 1 ? 'x' : '';
            $row->result_course = $row->course_type == 1 ? ($onl_result && $onl_result->result == 1 ? '' : 'x') : ($off_result && $off_result->result == 1 ? '' : 'x');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
