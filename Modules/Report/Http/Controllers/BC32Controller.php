<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Report\Entities\BC32;

class BC32Controller extends ReportController
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

        $from_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->from_date)));
        $to_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->to_date)));
        $type = $request->type;
        $training_form = $request->training_form ? explode(';', $request->training_form) : null;
        $unit = $request->unit;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC32::getQuery();
        if ($training_form) {
            $query->whereIn('id', $training_form);
        }
        
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        foreach ($rows as $row){
            $row->total = BC32::getTotalObject($training_form, $type, $from_date, $to_date, $unit);
            $row->join = BC32::getTotalJoin($training_form, $type, $from_date, $to_date, $unit);
            $row->completed = BC32::getTotalCompleted($training_form, $type, $from_date, $to_date, $unit);
            $row->not_join = $row->total - $row->join;
            $row->not_join = $row->not_join > 0 ? $row->not_join : 0;
            $row->absent = BC32::getTotalAbsent($training_form, $type, $from_date, $to_date, $unit);
        }

        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function dataChart(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $type = $request->type;
        $training_form = $request->training_form;
        $unit = $request->unit;
    
        if ($from_date) {
            $from_date = date_convert($from_date, '00:00:00');
        }
    
        if ($to_date) {
            $to_date = date_convert($to_date, '23:59:59');
        }
        
        $data = [];
        $data[] = [
            'Hình thức',
        ];
    
        $query = BC32::getQuery();
        $query->select([
            'id',
            'name'
        ]);
    
        if ($training_form) {
            $query->whereIn('id', $training_form);
        }
    
        $rows = $query->get();
        foreach ($rows as $row) {
            $data[] = [
                $row->name,
            ];
        }
    
        return \response()->json($data);
    }
}
