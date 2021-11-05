<?php

namespace Modules\Report\Http\Controllers;

use App\Profile;
use App\Models\Categories\TrainingForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Report\Entities\BC18;
use function PHPSTORM_META\elementType;

class BC18Controller extends ReportController
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
        $unit_id = $request->unit_id;
        $area_id = $request->area;
        $userCode = $request->userCode;
        $userName = $request->userName;
        // dd($userName);

        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC18::sql($from_date, $to_date, $unit_id, $userCode, $userName, $area_id);
        $count = $query->count();
        $query->orderBy('lh.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->start_date = get_date($row->created_at, 'H:i:s d/m/Y');
            $row->end_date = get_date($row->updated_at, 'H:i:s d/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
