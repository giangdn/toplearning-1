<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizTemplates;

use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\ReportNew\Entities\BC03;

class BC03Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        QuizTemplates::addGlobalScope(new DraftScope());
        $quiz_template = QuizTemplates::get();

        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'quiz_template' => $quiz_template,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->from_date && !$request->to_date && !$request->quiz_template_id)
            json_result([]);

        $quiz_template_id = $request->quiz_template_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC03::sql($from_date, $to_date, $quiz_template_id);
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->num_question_used = QuizQuestion::whereQuizId($row->id)
                ->where(function ($sub) use ($row){
                    $sub->orWhere('qcategory_id', '=', $row->cate_ques_id);
                    $sub->orWhereIn('question_id', function ($sub) use ($row){
                        $sub->select(['id'])
                            ->from('el_question')
                            ->where('category_id', '=', $row->cate_ques_id)
                            ->pluck('id')
                            ->toArray();
                    });
                })
                ->count();
            $row->num_list_question = Question::whereStatus(1)->whereCategoryId($row->cate_ques_id)->count();

            $total_question = 0;
            $total_question_right = 0;

            $update_attemplate = QuizUpdateAttempts::whereQuizId($row->id)->get();
            foreach ($update_attemplate as $item){
                $questions = json_decode($item['questions'], true);
                foreach ($questions as $question){
                    if ($question['score'] == $question['score_group'] && $question['category_id'] == $row->cate_ques_id){
                        $total_question_right += 1;
                    }
                }
                $total_question += count($questions);
            }
            if($total_question > 0 &&  $total_question_right > 0) {
                $row->percent_right = number_format(($total_question_right/$total_question)*100, 2) .'%';
            } else {
                $row->percent_right = '0%';
            }

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
