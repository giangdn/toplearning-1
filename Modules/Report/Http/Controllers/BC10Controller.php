<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\TrainingTeacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Report\Entities\BC10;

class BC10Controller extends ReportController
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
        $type = $request->type;
        
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC10::sql($from_date,$to_date,$type);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
