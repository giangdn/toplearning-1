<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Report\Entities\BC01;

class BC01Controller extends ReportController
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

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $user_id = $request->user_id;

        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC01::sql($user_id, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy('b.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row){
            $profile = Profile::find($row->user_id);
            $arr_unit = Unit::getTreeParentUnit($profile->unit_code);

            $row->fullname = $row->lastname . ' ' . $row->firstname;
            $row->title_name = $profile ? $profile->titles->name : '';
            $row->unit_name = $arr_unit ? $arr_unit[$profile->unit->level]->name : '';
            $row->parent_unit_name = $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '';
            $row->unit_name_level2 = $arr_unit ? $arr_unit[2]->name : '';
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->cost_commit = number_format($row->cost_commit,0,',','.');
            $row->cost_indemnify = number_format($row->cost_indemnify,0,',','.');
            $row->day_commit = get_date($row->day_commit, 'd/m/Y');
            $row->day_off = get_date($row->day_off, 'd/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
