<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Quiz\Entities\QuizType;
use Modules\Report\Entities\BC36;

class BC36Controller extends ReportController
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
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $quiz_type = $request->get('quiz_type')[0];
        
        if ($from_date) {
            $from_date = date_convert($from_date, '00:00:00');
        }
        
        if ($to_date) {
            $to_date = date_convert($to_date, '23:59:59');
        }
        
        if ($quiz_type) {
            $quiz_type = explode(';', $quiz_type);
        }
        
        $query = QuizType::query();
        $query->select([
            'id',
            'name'
        ]);
        
        if ($quiz_type) {
            $query->whereIn('id', $quiz_type);
        }
        //dd($query->toRawSql());
        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
    
        foreach ($rows as $row) {
            $total_completed = BC36::totalCompleted($from_date, $to_date, $row->id);
            $total_failed = BC36::totalFailed($from_date, $to_date, $row->id);
            $row->total_quiz1 = BC36::totalQuiz1($from_date, $to_date, $row->id);
            $row->total_quiz2 = BC36::totalQuiz2($from_date, $to_date, $row->id);
            $row->total_quiz3 = BC36::totalQuiz3($from_date, $to_date, $row->id);
            $row->total_register1 = BC36::totalRegister1($from_date, $to_date, $row->id);
            $row->total_register2 = BC36::totalRegister2($from_date, $to_date, $row->id);
            $row->total_register3 = BC36::totalRegister3($from_date, $to_date, $row->id);
            $row->rate = $total_failed > 0 ? round($total_completed / $total_failed, 2) : 100;
        }
        
        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function dataChart(Request $request)
    {
        if ($request->chart == 1) {
            return $this->chart1($request);
        }
    
        if ($request->chart == 2) {
            return $this->chart2($request);
        }
    
        return $this->chart3($request);
    }
    
    protected function chart1(Request $request) {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $quiz_type = $request->get('quiz_type')[0];
    
        if ($from_date) {
            $from_date = date_convert($from_date, '00:00:00');
        }
    
        if ($to_date) {
            $to_date = date_convert($to_date, '23:59:59');
        }
    
        if ($quiz_type) {
            $quiz_type = explode(';', $quiz_type);
        }
    
        $data = [];
        $data[] = [
            'Loại kỳ thi',
            'Sắp diễn ra',
            'Đang diễn ra',
            'Đã kết thúc'
        ];
    
        $query = QuizType::query();
        $query->select([
            'id',
            'name'
        ]);
    
        if ($quiz_type) {
            $query->whereIn('id', $quiz_type);
        }
    
        $rows = $query->get();
        foreach ($rows as $row) {
            $data[] = [
                $row->name,
                BC36::totalQuiz1($from_date, $to_date, $row->id),
                BC36::totalQuiz2($from_date, $to_date, $row->id),
                BC36::totalQuiz3($from_date, $to_date, $row->id)
            ];
        }
    
        return \response()->json($data);
    }
    
    protected function chart2(Request $request) {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $quiz_type = $request->get('quiz_type')[0];
        
        if ($from_date) {
            $from_date = date_convert($from_date, '00:00:00');
        }
        
        if ($to_date) {
            $to_date = date_convert($to_date, '23:59:59');
        }
        
        if ($quiz_type) {
            $quiz_type = explode(';', $quiz_type);
        }
        
        $data = [];
        $data[] = [
            'Loại kỳ thi',
            'Sắp diễn ra',
            'Đang diễn ra',
            'Đã kết thúc'
        ];
        
        $query = QuizType::query();
        $query->select([
            'id',
            'name'
        ]);
        
        if ($quiz_type) {
            $query->whereIn('id', $quiz_type);
        }
        
        $rows = $query->get();
        foreach ($rows as $row) {
            $data[] = [
                $row->name,
                BC36::totalRegister1($from_date, $to_date, $row->id),
                BC36::totalRegister2($from_date, $to_date, $row->id),
                BC36::totalRegister3($from_date, $to_date, $row->id)
            ];
        }
        
        return \response()->json($data);
    }
    
    protected function chart3(Request $request) {
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $quiz_type = $request->get('quiz_type')[0];
        
        if ($from_date) {
            $from_date = date_convert($from_date, '00:00:00');
        }
        
        if ($to_date) {
            $to_date = date_convert($to_date, '23:59:59');
        }
        
        if ($quiz_type) {
            $quiz_type = explode(';', $quiz_type);
        }
        
        $data = [];
        $data[] = [
            'Loại kỳ thi',
            'Đạt',
            'Không đạt',
        ];
        
        $query = QuizType::query();
        $query->select([
            'id',
            'name'
        ]);
        
        if ($quiz_type) {
            $query->whereIn('id', $quiz_type);
        }
        
        $rows = $query->get();
        foreach ($rows as $row) {
            $data[] = [
                $row->name,
                BC36::totalCompleted($from_date, $to_date, $row->id),
                BC36::totalFailed($from_date, $to_date, $row->id),
            ];
        }
        
        return \response()->json($data);
    }
}
