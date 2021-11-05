<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\ReportNew\Entities\BC22;

class BC22Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $type = $request->type;
        $isSubmit = $request->isSubmit;
        if (!$isSubmit)
            return;
        $query = BC22::sql($type,$start_date,$end_date);
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row){
            $row->created_date = get_date($row->date_action);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
