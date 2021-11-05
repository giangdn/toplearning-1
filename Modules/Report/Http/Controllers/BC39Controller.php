<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Report\Entities\BC39;

class BC39Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        $day = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'day' => $day
        ]);
    }

    public function dataChart(Request $request) {
        $day = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));

        $data = [];
        $data[] = [
            'Ngày',
            'Số lượng',
        ];

        for($i = 1; $i <= $day; $i++){
            $data[] = [
                $i,
                BC39::countViewVideoInMonth($i),
            ];
        }

        return \response()->json($data);
    }
}
