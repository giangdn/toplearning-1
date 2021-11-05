<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\TrainingCost;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineRegister;
use Modules\ReportNew\Entities\BC27;

class BC27Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        TrainingCost::addGlobalScope(new CompanyScope());
        $training_cost = TrainingCost::query()->orderBy('type')->get();
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
        $course_type = $request->course_type;

        if (!$from_date && !$to_date)
            json_result([]);

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC27::sql($course_type, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        TrainingCost::addGlobalScope(new CompanyScope());
        $training_cost = TrainingCost::query()->orderBy('type')->get();
        foreach ($rows as $row){
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);

            if ($row->course_type == 1){
                $num_user = OnlineRegister::whereCourseId($row->course_id)->whereStatus(1)->count();
            }else{
                $num_user = OfflineRegister::whereCourseId($row->course_id)->whereStatus(1)->count();
                $row->course_time = OfflineSchedule::whereCourseId($row->course_id)->count();
            }
            $row->num_user = $num_user;

            foreach ($training_cost as $cost){
                if ($row->course_type == 1){
                    $course_cost = OnlineCourseCost::whereCourseId($row->course_id)->whereCostId($cost->id)->first();
                }else{
                    $course_cost = OfflineCourseCost::whereCourseId($row->course_id)->whereCostId($cost->id)->first();
                }

                $row->{'cost'.$cost->id} = isset($course_cost->actual_amount) ? number_format($course_cost->actual_amount, 2) : 0;
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
