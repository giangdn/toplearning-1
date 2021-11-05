<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Report\Entities\BC20;
use function PHPSTORM_META\elementType;

class BC20Controller extends ReportController
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

        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC20::sql($from_date, $to_date);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->user_name = $row->lastname . ' ' . $row->firstname;

//            if (empty($row->parent_name)){
//                $row->parent = $row->unit_name;
//                $row->unit = '';
//            }else{
//                $row->parent = $row->parent_name;
//                $row->unit = $row->unit_name;
//            }

            if ($row->training_form == 1){
                $row->training_form = 'Tự học';
            }
            if ($row->training_form == 2){
                $row->training_form = 'TĐV kèm cặp';
            }
            if ($row->training_form == 3){
                $row->training_form = 'Nội bộ';
            }
            if ($row->training_form == 4){
                $row->training_form = 'Thuê ngoài';
            }

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
