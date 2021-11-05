<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Rating\Entities\RatingCourseAnswer;
use Modules\Rating\Entities\RatingQuestionAnswer;
use Modules\Report\Entities\BC11;

class BC11Controller extends ReportController
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
        if (!$request->course)
            json_result([]);
        $course = $request->course;
        $type = $request->type;
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC11::sql($course, $type);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->answer = [];
            $answers = RatingQuestionAnswer::where('question_id', '=', $row->id)->get();
            foreach($answers as $item){
                $row->answer[] = $item->name;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
