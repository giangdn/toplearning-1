<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRank;

use Modules\Report\Entities\BC22;
class BC22Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $quiz_id = $request->quiz_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        if ($quiz_id){
            $ranks = QuizRank::where('quiz_id', '=', $quiz_id)->get();

            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'asc');

            $query = BC22::sql($quiz_id,  $from_date, $to_date);
            $query->orderBy('c.'.$sort, $order);
            $rows = $query->get();
        }

        $list = function ($title_id, $quiz_id){
            $query = \DB::query()
                ->from('el_quiz_register AS a')
                ->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id')
                ->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code')
                ->where('c.id', '=', $title_id)
                ->where('a.quiz_id', '=', $quiz_id)
                ->count('a.user_id');

            return $query;
        };

        $result = function ($title_id, $quiz_id) {
            $query = \DB::query()
                ->from('el_quiz_result AS b')
                ->leftJoin('el_profile AS c', 'c.user_id', '=', 'b.user_id')
                ->leftJoin('el_titles AS d', 'd.code', '=', 'c.title_code')
                ->where('d.id', '=', $title_id)
                ->where('b.quiz_id', '=', $quiz_id)
                ->get(['b.reexamine', 'b.grade']);

            return $query;
        };

        $quiz = Quiz::where('status', '=', 1)->get();
        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'quiz' => $quiz,
            'ranks' => $quiz_id ? $ranks : '',
            'rows' => $quiz_id ? $rows : '',
            'list' => $list,
            'result' => $result,
            'quiz_id' => $quiz_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }
}
