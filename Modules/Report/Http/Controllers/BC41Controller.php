<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\QuizResult;

use Modules\Report\Entities\BC41;
class BC41Controller extends ReportController
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
        if (!$request->title_id)
            json_result([]);

        $title_id = $request->title_id;

        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC41::sql($title_id);
        $count = $query->count();
        $query->orderBy('a.user_id', $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->user_code = Profile::usercode($row->user_id);
            $row->user_name = Profile::fullname($row->user_id);

            $profile = Profile::find($row->user_id);
            $arr_unit = Unit::getTreeParentUnit($profile->unit_code);
            $row->unit_name = $arr_unit ? $arr_unit[$profile->unit->level]->name : '';
            $row->parent_unit_name = $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '';
            $row->unit_name_level2 = $arr_unit ? $arr_unit[2]->name : '';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
