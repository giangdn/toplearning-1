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
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizPart;
use function PHPSTORM_META\elementType;

use Modules\Report\Entities\BC27;
class BC27Controller extends ReportController
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
        if (!$request->quiz_id && !$request->from_date && !$request->to_date && !$request->user_id)
            json_result([]);

        $quiz_id = $request->quiz_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $user_id = $request->user_id;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC27::sql($quiz_id,  $from_date, $to_date, $user_id);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $quiz_attempt = $this->getStatus($user_id, $quiz_id);

            $status = '';
            switch ($quiz_attempt->state) {
                case 'inprogress': $status = 'Đang làm bài'; break;
                case 'completed': $status = 'Hoàn thành'; break;
            }
            $row->status = $status;
            $row->grade = number_format($row->grade, 1);
            $row->full_name = $row->lastname .' '. $row->firstname;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getStatus($user_id, $quiz_id){
        $query = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->groupBy(['state'])
            ->select('state');

        return $query->first();
    }
}
