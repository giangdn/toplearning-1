<?php

namespace Modules\Report\Http\Controllers;
use App\Models\Categories\Titles;
use Illuminate\Http\Request;
use Modules\Report\Entities\BC24;

class BC24Controller extends ReportController
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

        $unit_id = $request->unit_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC24::sql($unit_id, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->intend = get_date($row->start_date, 'm/Y');
            $row->attach = $row->attach ? 'x' : '';
            $titles = array_values(json_decode($row->title,true));
            $arr_title = [];
            foreach ($titles as $item){
                $title = Titles::find($item);
                $arr_title[] = $title->name;
            }
            $row->title = $arr_title;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
