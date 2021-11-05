<?php

namespace Modules\Report\Http\Controllers;

use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use function PHPSTORM_META\elementType;

use Modules\Report\Entities\BC21;
class BC21Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $quiz = Quiz::where('status', '=', 1)->get();

        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'quiz' => $quiz,
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->quiz_id && !$request->from_date && !$request->to_date)
            json_result([]);

        $quiz_id = $request->quiz_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'title_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC21::sql($quiz_id,  $from_date, $to_date);
        $count = $query->count();
        $query->orderBy('c.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $list = \DB::query()
                ->from('el_quiz_register AS a')
                ->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id')
                ->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code')
                ->where('c.id', '=', $row->title_id)
                ->where('a.type', '=', 1)
                ->where('a.quiz_id', '=', $quiz_id)
                ->get();

            $absent = 0;
            foreach ($list as $item){
                $quiz_result = QuizResult::where('user_id', '=', $item->user_id)->first();
                if (is_null($quiz_result)){
                    $absent++;
                }
            }

            $row->list = $list->count();
            $row->absent = $absent;
            $row->reality = $list->count() - $absent;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
