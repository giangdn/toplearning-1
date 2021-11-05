<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Profile;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Question;
use Modules\ReportNew\Entities\BC15;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;

class BC15Controller extends ReportNewController
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
        $status_id = $request->status_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $title_id = $request->title_id;

        if (!$title_id)
            json_result([]);

        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC15::sql($title_id, $status_id, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

//        $subjects = TrainingRoadmap::where(['title_id'=>$title_id])->get('id');
        foreach ($rows as $row){
            $unit = Unit::whereCode($row->unit1_code)->first();
            $unit_type = UnitType::find(@$unit->type);

            $row->unit_type = @$unit_type->name;

            $subjects = json_decode($row->subject,true);
            foreach ($subjects as $index => $subject) {
                $row->{'subject'.$subject['code']}= $subject['type'];
            }
            $row->join_date = get_date($row->join_company);

            $profile = Profile::find($row->user_id);
            switch ($profile->status){
                case 0:
                    $status = trans('backend.inactivity'); break;
                case 1:
                    $status = trans('backend.doing'); break;
                case 2:
                    $status = trans('backend.probationary'); break;
                case 3:
                    $status = trans('backend.pause'); break;
            }

            $row->status = $status;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
