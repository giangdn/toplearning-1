<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Report\Entities\BC47;

class BC47Controller extends ReportController
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
        if (!$request->quiz_template_id)
            json_result([]);

        $quiz_template_id = $request->quiz_template_id;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC47::sql($quiz_template_id);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->question_name = @Question::find($row->question_id)->name;
            $row->percent_right = number_format(($row->num_correct_answer/$row->num_answer)*100, 2) . '%';
            if ($row->question_type == 'essay'){
                $row->text_question_type = 'Tự luận';
            }
            if ($row->question_type == 'matching'){
                $row->text_question_type = 'Nối câu';
            }
            if ($row->question_type == 'fill_in'){
                $row->text_question_type = 'Điền trống';
            }
            if ($row->question_type == 'multiple-choise'){
                $row->text_question_type = 'Trắc nghiệm';
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
