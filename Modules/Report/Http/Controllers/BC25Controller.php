<?php

namespace Modules\Report\Http\Controllers;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Report\Entities\BC25;

class BC25Controller extends ReportController
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
        if (!$request->from_date && !$request->to_date && !$request->unit_id)
            json_result([]);

        $unit_id = $request->unit_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC25::sql($unit_id, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->unit_name = Unit::find($unit_id)->name;
            $row->quantity = OfflineRegister::where('course_id', '=', $row->id)
                ->where('status', '=', 1)
                ->count();

            $row->date = get_date($row->start_date, 'd/m/Y') . ' <span class="fa fa-arrow-right"></span> ' . get_date($row->end_date, 'd/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
