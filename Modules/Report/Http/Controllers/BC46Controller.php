<?php

namespace Modules\Report\Http\Controllers;

use App\Profile;
use App\Role;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizType;

use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Report\Entities\BC46;
class BC46Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $quiz_template = QuizTemplates::get();

        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'quiz_template' => $quiz_template
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->from_date && !$request->to_date && !$request->quiz_template_id)
            json_result([]);

        $quiz_template_id = $request->quiz_template_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'a.id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC46::sql($from_date, $to_date, $quiz_template_id);
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->num_question_used = QuizQuestion::whereQuizId($row->id)->count();
            $row->num_list_question = Question::whereStatus(1)->count();

            $total_question = 0;
            $total_question_right = 0;

            $update_attemplate = QuizUpdateAttempts::whereQuizId($row->id)->get();
            foreach ($update_attemplate as $item){
                $questions = json_decode($item['questions'], true);
                foreach ($questions as $question){
                    if ($question['score'] == $question['score_group']){
                        $total_question_right += 1;
                    }
                }
                $total_question += count($questions);
            }
            $row->percent_right = number_format(($total_question_right/$total_question)*100, 2) .'%';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
