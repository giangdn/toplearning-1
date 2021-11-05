<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Area;
use App\Profile;
use App\Role;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizType;

use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\ReportNew\Entities\BC02;

class BC02Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        Role::addGlobalScope(new DraftScope());
        $role = Role::where('type', '=', 2)->get();

        QuizType::addGlobalScope(new DraftScope());
        $quiz_type = QuizType::get();

        Quiz::addGlobalScope(new DraftScope());
        $quiz = Quiz::whereStatus(1)->get();

        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'role' => $role,
            'quiz_type' => $quiz_type,
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
        $quiz_id = $request->quiz_id;
        $role_id = $request->role_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'a.id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC02::sql($from_date, $to_date, $type_id, $role_id, $quiz_id);
        $count = $query->count();
        $query->orderBy('el_quiz_attempts.'.$sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $quiz = Quiz::find($row->quiz_id);
            $quiz_template = QuizTemplates::find($quiz->quiz_template_id);
            $role = Role::find($role_id);
            $type = QuizType::find($row->type_id);

            $unit_name = '';
            $unit_parrent_name = '';
            $title_name = '';
            $area_name = '';
            if ($row->type == 1){
                $profile = Profile::query()->find($row->user_id);
                $full_name = $profile->getFullName();
                $unit_name = @$profile->unit->name;
                $unit_parrent_name = @$profile->unit->parent->name;
                $title_name = @$profile->titles->name;

                $area = Area::find(@$profile->unit->area_id);
                $area_name = @$area->name;
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
            $row->area_name = $area_name;
            $row->unit_name = $unit_name;
            $row->unit_parrent_name = $unit_parrent_name;
            $row->title_name = $title_name;
            $row->email = @$profile->email;
            $row->start_date = date('H:i:s d/m/Y', $row->timestart);
            $row->execution_time = $row->timefinish > 0 ? calculate_time_span($row->timestart, $row->timefinish) : '';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
