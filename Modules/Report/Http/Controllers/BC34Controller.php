<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\TrainingForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Report\Entities\BC33;

class BC34Controller extends ReportController
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
        
        $optradio = $request->optradio;
        $day = $request->day;
        $month = $request->month;
        $year = $request->year;
        $age_from = explode(';', $request->age_from[0]);
        $age_to = explode(';', $request->age_to[0]);
        $seniority_from = explode(';', $request->seniority_from[0]);
        $seniority_to = explode(';', $request->seniority_to[0]);
        $unit = $request->unit;
        
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        
        $query = \DB::query();
        $query->select([
            'id',
            'name'
        ]);
        
        $query->from('el_training_form');
        $query->orderBy('name', 'ASC');
        
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        foreach ($rows as $row){
            //$row->active = BC33::countCourseActive([$row->id], $type, $from_date, $to_date);
            //$row->upcoming = BC33::countCourseUpcoming([$row->id], $type, $from_date, $to_date);
            //$row->finished = BC33::countCourseFinished([$row->id], $type, $from_date, $to_date);
        }
        
        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function dataChart(Request $request) {
        $from_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->from_date)));
        $to_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->to_date)));
        $type = $request->type;
        $training_form = $request->training_form;
        $forms = TrainingForm::query();
        if ($training_form) {
            $forms->whereIn('id', $training_form);
        }
        
        $forms->orderBy('name', 'ASC');
        $forms = $forms->get();
        
        $data = [];
        $data[] = [
            trans('backend.from'),
            'Đang diễn ra',
            'Sắp diễn ra',
            'Đã kết thúc'
        ];
        
        foreach ($forms as $form) {
            $data[] = [
                $form->name,
                BC33::countCourseActive([$form->id], $type, $from_date, $to_date),
                BC33::countCourseUpcoming([$form->id], $type, $from_date, $to_date),
                BC33::countCourseFinished([$form->id], $type, $from_date, $to_date)
            ];
        }
        
        return \response()->json($data);
    }
}
