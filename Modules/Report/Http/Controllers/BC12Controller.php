<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Report\Entities\BC12;

class BC12Controller extends ReportController
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
        if (!$request->course)
            json_result([]);
        $course = $request->course;
        $type = $request->type;
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC12::sql($course, $type);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row){
            $row->full_name = $row->lastname. ' ' .$row->firstname;
            $row->gender = $row->gender == 1 ? 'Nam' : 'Nữ';

            $profile = Profile::find($row->user_id);
            $arr_unit = Unit::getTreeParentUnit($profile->unit_code);
            $row->unit_name = $arr_unit ? $arr_unit[$profile->unit->level]->name : '';
            $row->parent_unit_name = $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '';
            $row->unit_name_level2 = $arr_unit ? $arr_unit[2]->name : '';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
