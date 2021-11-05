<?php

namespace Modules\Report\Http\Controllers;

use App\Profile;
use App\Role;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizType;

use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Report\Entities\BC45;
class BC45Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $role = Role::where('type', '=', 2)->get();
        $quiz_type = QuizType::get();

        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'role' => $role,
            'quiz_type' => $quiz_type
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->from_date && !$request->to_date)
            json_result([]);

        $type_id = $request->type_id;
        $role_id = $request->role_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'a.id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC45::sql($from_date, $to_date, $type_id, $role_id);
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $quiz = Quiz::find($row->quiz_id);
            $quiz_template = QuizTemplates::find($quiz->quiz_template_id);
            $role = Role::find($role_id);
            $type = QuizType::find($type_id);

            $unit_name = '';
            $title_name = '';
            if ($row->type == 1){
                $profile = Profile::query()->find($row->user_id);
                $full_name = $profile->getFullName();
                $unit_name = @$profile->unit->name;
                $title_name = @$profile->titles->name;
            }else{
                $profile = QuizUserSecondary::find($row->user_id);
                $full_name = $profile->name;
            }

            $row->quiz_name = $quiz->name;
            $row->role_name = $role ? $role->description : '';
            $row->type_name = $type ? $type->name : '';
            $row->quiz_template = $quiz_template ? $quiz_template->name : '';
            $row->full_name = $full_name;
            $row->user_code = @$profile->code;
            $row->unit_name = $unit_name;
            $row->title_name = $title_name;
            $row->email = @$profile->email;
            $row->start_date = date('H:i:s d/m/Y', $row->timestart);
            $row->execution_time = $row->timefinish > 0 ? calculate_time_span($row->timestart, $row->timefinish) : '';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
