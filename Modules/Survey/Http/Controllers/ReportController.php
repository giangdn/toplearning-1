<?php

namespace Modules\Survey\Http\Controllers;

use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Entities\SurveyUserAnswer;
use Modules\Survey\Entities\SurveyUserCategory;
use Modules\Survey\Entities\SurveyUserQuestion;
use Modules\Survey\Exports\ReportExport;

class ReportController extends Controller
{
    public function index($survey_id)
    {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        return view('survey::backend.report.index', [
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'survey_id' => $survey_id,
        ]);
    }

    public function getData($survey_id, Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SurveyUser::query();
        $query->select([
            'a.*', 
            'b.user_id', 
            'b.code', 
            'b.lastname', 
            'b.email', 
            'b.firstname', 
            'c.name AS title_name', 
            'd.name AS unit_name',
            'e.name AS unit_manager',
        ]);
        $query->from('el_survey_user AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id','=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code','=', 'b.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code','=', 'd.parent_code');
        $query->where('a.survey_id', '=', $survey_id);
        $query->where('a.send', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }
        if (!is_null($status)) {
            $query->where('b.status', '=', $status);
        }
        if ($request->area) {
            $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->WhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.unit_code', $unit_id);
                $sub_query->orWhere('d.id', '=', $unit->id);
            });
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('b.title_code', '=', $title->code);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->profile_name = $row->lastname .' '. $row->firstname;
            $row->edit_url = route('module.survey.report.edit', ['survey_id' => $survey_id, 'user_id' => $row->user_id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($survey_id, $user_id) {
        $survey = Survey::find($survey_id);
        $page_title = $survey->name;
        $user = Profile::find($user_id);
        $unit = Unit::where('code', '=', $user->unit_code)->first();
        $title = Titles::where('code', '=', $user->title_code)->first();

        $survey_user = SurveyUser::where('user_id', '=', $user_id)
            ->where('survey_id', '=', $survey_id)->where('send', '=', 1)->first();

        $survey_user_categories = SurveyUserCategory::where('survey_user_id', '=', $survey_user->id)->get();

        $survey_user_question = function ($survey_user_category_id){
            return SurveyUserQuestion::where('survey_user_category_id', '=', $survey_user_category_id)->get();
        };

        $survey_user_answer = function($survey_user_question_id){
            return SurveyUserAnswer::where('survey_user_question_id', '=', $survey_user_question_id)->get();
        };

        return view('survey::backend.report.form', [
            'survey_user_categories' => $survey_user_categories,
            'survey_user_question' => $survey_user_question,
            'survey_user_answer' => $survey_user_answer,
            'survey_user' => $survey_user,
            'survey' => $survey,
            'user' => $user,
            'unit' => $unit,
            'title' => $title,
            'page_title' => $page_title,
        ]);
    }

    public function export($survey_id)
    {
        return (new ReportExport($survey_id))->download('bao_cao_tong_hop_'. date('d_m_Y') .'.xlsx');
    }

}
