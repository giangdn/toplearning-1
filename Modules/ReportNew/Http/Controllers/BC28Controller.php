<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\ProfileView;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\ReportNew\Entities\BC28;
use function GuzzleHttp\json_decode;

class BC28Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        Quiz::addGlobalScope(new DraftScope());
        $quiz = Quiz::whereStatus(1)->get();
        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'quiz' => $quiz,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $quiz_id = $request->quiz_id;

        if (!$from_date && !$to_date && !$quiz_id)
            json_result([]);

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC28::sql($from_date, $to_date, $quiz_id);
        $count = $query->count();
        $query->orderBy('user_id', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $quiz = Quiz::find($row->quiz_id);
            $quiz_result = QuizResult::whereQuizId($row->quiz_id)->whereUserId($row->user_id)->whereType($row->type)->first();
            $quiz_type = QuizType::find(@$quiz->type_id);

            if ($row->type == 1){
                $profile = ProfileView::whereUserId($row->user_id)->first();

                $unit = Unit::whereCode($profile->unit_code)->first();
                $area = Area::find(@$unit->area_id);
            }else{
                $profile = QuizUserSecondary::find($row->user_id);
            }


            $quiz_attempt = QuizAttempts::whereQuizId($row->quiz_id)->whereUserId($row->user_id)->where('type', $row->type)->latest()->first();
            $quiz_question = QuizQuestion::whereQuizId($row->quiz_id)->count();
            $quiz_update_attempt = QuizUpdateAttempts::whereQuizId($row->quiz_id)->whereUserId($row->user_id)->whereType($row->type)->latest()->first();

            $num_true = 0;
            if ($quiz_update_attempt){
                $questions = json_decode($quiz_update_attempt->questions);
                foreach ($questions as $question){
                    if ($question->score_group == $question->score){
                        $num_true += 1;
                    }
                }
            }

            $row->quiz_name = $quiz->name;
            $row->type_name = @$quiz_type->name;
            $row->user_code = $profile->code;
            $row->full_name = $row->type == 1 ? $profile->full_name : $profile->name;
            $row->title_name = $row->type == 1 ? $profile->title_name : '_';
            $row->unit_name = $row->type == 1 ? $profile->unit_name : '_';
            $row->unit_parent_name = $row->type == 1 ? $profile->parent_unit_name : '_';
            $row->area_name = $row->type == 1 ? @$area->name : '_';
            $row->email = $profile->email;
            $row->status = ($quiz_result && $quiz_result->result == 1) ? 'Hoàn thành' : 'Chưa hoàn thành';
            $row->start_date = isset($quiz_attempt->timestart) ? date('H:i:s d/m/Y', @$quiz_attempt->timestart) : '';
            $row->end_date = isset($quiz_attempt->timefinish) ? date('H:i:s d/m/Y', @$quiz_attempt->timefinish) : '';
            $row->execution_time = calculate_time_span(@$quiz_attempt->timefinish, @$quiz_attempt->timestart);
            $row->score = $quiz_result ? $quiz_result->grade : '';
            $row->num_true = $num_true;
            $row->num_false = $quiz_question - $num_true;
            $row->percent_true = number_format(($row->num_true / ($quiz_question > 0 ? $quiz_question : 1)) * 100, 2);
            $row->percent_false = number_format(($row->num_false / ($quiz_question > 0 ? $quiz_question : 1)) * 100, 2);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
