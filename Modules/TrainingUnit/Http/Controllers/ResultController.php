<?php

namespace Modules\TrainingUnit\Http\Controllers;

use App\Models\Categories\Area;
use App\Parameter;
use App\Permission;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ManagerCourse\Entities\ManagerCourseComplete;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\TrainingUnit\Exports\ResultExport;

class ResultController extends Controller
{
    public function index()
    {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $max_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };
        return view('trainingunit::backend.result.index', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'max_area' => $max_area,
            'level_name_area' => $level_name_area,
        ]);
    }

    public function getUser(Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');
        $area = $request->input('area');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $unit_manager = UnitManager::getIdUnitManagedByUser();

        $query = Profile::query();
        $query->select([
            'a.id',
            'a.user_id',
            'a.code',
            'a.firstname',
            'a.lastname',
            'a.status',
            'b.name AS unit_name',
            'e.name AS unit_manager',
            'c.name AS title_name',
            'd.name AS area_name',
        ]);
        $query->from('el_profile AS a');
        $query->leftJoin('el_unit AS b', 'b.code', '=', 'a.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'b.parent_code');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'a.title_code');
        $query->leftJoin('el_area AS d', 'd.code', '=', 'a.area_code');
        $query->where('a.user_id', '>', 2);

        if (!Permission::isAdmin()){
            $query->whereIn('b.id', $unit_manager);
        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('a.status', '=', $status);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.id', $unit_id);
                $sub_query->orWhere('b.id', '=', $unit->id);
            });
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('a.title_code', '=', $title->code);
        }

        if ($area){
            $area = Area::whereIn('id', explode(';', $area))->latest('id')->first();
            $area_id = Area::getArrayChild($area->code);

            $query->where(function ($sub_query) use ($area_id, $area) {
                $sub_query->orWhereIn('d.id', $area_id);
                $sub_query->orWhere('d.id', '=', $area->id);
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.training_unit.result.user', ['user_id' => $row->user_id]);
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);
            $row->area_url = route('module.backend.user.get_area', ['user_id' => $row->user_id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getResult($user_id, Request $request){
        $profile = Profile::find($user_id);
        $titles = Titles::where('code', '=', $profile->title_code)->first();

        $training_programs = TrainingRoadmap::query()
            ->select([
                'a.training_program_id',
                'b.name',
            ])
            ->from('el_trainingroadmap as a')
            ->leftJoin('el_training_program as b', 'b.id', '=', 'a.training_program_id')
            ->where('a.title_id', '=', $titles->id)
            ->groupBy(['a.training_program_id', 'b.name'])
            ->get();

        $subjects = function ($training_program){
            return TrainingRoadmap::query()
                ->from('el_trainingroadmap as a')
                ->leftJoin('el_subject as b', 'b.id', '=', 'a.subject_id')
                ->where('a.training_program_id', '=', $training_program)
                ->get();
        };

        $results = function ($user_id, $subject_id){
            return ManagerCourseComplete::query()
                ->where('user_id', '=', $user_id)
                ->where('subject_id', '=', $subject_id)
                ->first();
        };

        $teacher = function ($teacher_id){
           return TrainingTeacher::find($teacher_id);
        };

        $parameter = function ($type){
            return Parameter::where('type', '=', $type)->first();
        };

        return view('trainingunit::backend.result.result', [
            'profile' => $profile,
            'results' => $results,
            'titles' => $titles,
            'subjects' => $subjects,
            'teacher' => $teacher,
            'training_programs' => $training_programs,
            'parameter' => $parameter
        ]);
    }

    public function exportResult($user_id)
    {
        return (new ResultExport($user_id))->download('ket_qua_dao_tao_'. date('d_m_Y') .'.xlsx');
    }
}
