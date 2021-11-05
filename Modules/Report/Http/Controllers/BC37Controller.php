<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\DailyTraining\Entities\DailyTrainingVideo;
use Modules\Report\Entities\BC37;

class BC37Controller extends ReportController
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
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Unit::where('status', '=', 1);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->number = BC37::countVideoInMonth($row->id);
        }

        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function dataChart(Request $request) {
        $department = Unit::where('status', '=', 1)->get();

        $data = [];
        $data[] = [
            '',
            'Số lượng',
        ];

        foreach ($department as $item){
            $data[] = [
                $item->name,
                BC37::countVideoInMonth($item->id),
            ];
        }

        return \response()->json($data);
    }
}
