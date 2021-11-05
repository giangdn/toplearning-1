<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Role;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizType;
use Modules\ReportNew\Entities\BC01;

class BC01Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        Quiz::addGlobalScope(new DraftScope());
        $quiz = Quiz::whereStatus(1)->get();

        Role::addGlobalScope(new DraftScope());
        $role = Role::where('type', '=', 2)->get();
        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'role' => $role,
            'quiz' => $quiz,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->from_date && !$request->to_date)
            json_result([]);

        $type_id = $request->quiz_type;
        $role_id = $request->role_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $quiz_id = $request->quiz_id;

        $sort = $request->input('sort', 'a.id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC01::sql($from_date, $to_date, $type_id, $role_id, $quiz_id);
        $count = $query->count();
        $query->orderBy('el_quiz.'.$sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $quiz_template = QuizTemplates::find($row->quiz_template_id);
            $role = Role::find($role_id);
            $type = QuizType::find($row->type_id);

            $start_date = '';
            $end_date = '';

            $qdate = QuizPart::where('quiz_id', '=', $row->id)
                ->first(\DB::raw('MIN(start_date) as start_date'));
            if ($qdate->exists()) {
                $start_date = $qdate->start_date;
            }

            $qdate = QuizPart::where('quiz_id', '=', $row->id)
                ->first(\DB::raw('MAX(end_date) as end_date'));
            if ($qdate->exists()) {
                $end_date = $qdate->end_date;
            }
            $quiz_result = QuizResult::whereQuizId($row->id)->where('timecompleted', '>', 0)->get();
            $count_quiz_result = $quiz_result->count();

            $row->quiz_name = $row->name;
            $row->role_name = $role ? $role->description : '';
            $row->type_name = $type ? $type->name : '';
            $row->quiz_template = $quiz_template ? $quiz_template->name : '';
            $row->num_question = QuizQuestion::whereQuizId($row->id)->count();
            $row->limit_time = $row->limit_time . ' phút';
            $row->start_date = get_date($start_date, 'H:i d/m/Y');
            $row->end_date = $end_date ? get_date($end_date, 'H:i d/m/Y') : '';
            $row->num_register = QuizRegister::whereQuizId($row->id)->count();
            $row->num_doquiz = $count_quiz_result;
            $row->num_absent = $row->num_register - $row->num_doquiz;
            $total_score = 0;
            $score_03 = 0;
            $score_35 = 0;
            $score_57 = 0;
            $score_78 = 0;
            $score_89 = 0;
            $score_910 = 0;
            foreach ($quiz_result as $result){
                $total_score += $result->grade;
                if ($result->grade >= 0 && $result->grade < 3){
                    $score_03 += 1;
                }
                if ($result->grade >= 3 && $result->grade < 5){
                    $score_35 += 1;
                }
                if ($result->grade >= 5 && $result->grade < 7){
                    $score_57 += 1;
                }
                if ($result->grade >= 7 && $result->grade < 8){
                    $score_78 += 1;
                }
                if ($result->grade >= 8 && $result->grade < 9){
                    $score_89 += 1;
                }
                if ($result->grade >= 9){
                    $score_910 += 1;
                }
            }

            $row->score_average = number_format($total_score/($count_quiz_result > 0 ? $count_quiz_result : 1), 2);
            $row->score_03 = $score_03;
            $row->score_35 = $score_35;
            $row->score_57 = $score_57;
            $row->score_78 = $score_78;
            $row->score_89 = $score_89;
            $row->score_910 = $score_910;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
