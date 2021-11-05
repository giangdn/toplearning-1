<?php

namespace Modules\Potential\Http\Controllers;

use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\KPI;
use App\Profile;
use App\Certificate;
use Illuminate\Support\Facades\DB;
use Modules\Capabilities\Entities\CapabilitiesGroupPercent;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Potential\Entities\Potential;
use Modules\Potential\Exports\ExportCourse;
use Modules\Potential\Exports\PotentialExport;
use Modules\Potential\Exports\PotentialSearchExport;
use Modules\Potential\Imports\KPIImports;
use Modules\Potential\Imports\PotentialImports;
use Modules\Quiz\Entities\QuizResult;

class BackendController extends Controller{

    public function index()
    {
        $errors = session()->get('errors');
        \Session::forget('errors');

        $cert = Certificate::get();

        return view('potential::backend.potential.index', [
            'cert' => $cert,
        ]);
    }

    public function course($user_id){
        $potential = Potential::where('user_id', '=', $user_id)->first();
        $profile = Profile::find($potential->user_id);
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $subject = Potential::getCourse($title->id);

        $course = function ($training_program_id, $subject_id, $cours_type, $user_id){
            $query = \DB::query();
            $query->select('a.id', 'a.course_type', 'a.start_date', 'a.end_date')
                ->from('el_course_view as a')
                ->leftJoin('el_course_register_view as b', function ($sub){
                    $sub->on('b.course_id', '=', 'a.course_id');
                    $sub->on('b.course_type', '=', 'a.course_type');
                })
                ->where('b.user_id', '=', $user_id)
                ->where('a.training_program_id', '=', $training_program_id)
                ->where('a.subject_id', '=', $subject_id)
                ->where('a.course_type', '=', $cours_type)
                ->where('a.status', '=', 1)
                ->where('b.status', '=', 1);

            return $query->first();
        };

        $result_course = function($user_id, $course_id, $course_type){
            if ($course_type == 1){
                $onl = OnlineCourseActivity::where('course_id', '=', $course_id)
                    ->where('activity_id', '=', 2)->first();

                $result = null;
                if ($onl->subject_id){
                    $result = QuizResult::where('quiz_id', '=', $onl->subject_id)
                        ->where('user_id', '=', $user_id)->first();
                }

                return $result;
            }
            else{
                $off = OfflineCourse::find($course_id);
                $result = null;
                if ($off->quiz_id){
                    $result = QuizResult::where('quiz_id', '=', $off->quiz_id)
                        ->where('user_id', '=', $user_id)->first();
                }
                return $result;
            }
        };

        return view('potential::backend.potential.course', [
            'course' => $course,
            'subject' => $subject,
            'result_course' => $result_course,
            'profile' => $profile,
        ]);
    }
    public function getData(Request $request) {
        $cert = $request->cert;

        $from_percent = $request->input('from_percent');
        $to_percent = $request->input('to_percent');

        $from_year = $request->input('from_year');
        $to_year = $request->input('to_year');

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->input('unit');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Potential::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.code',
            'b.user_id',
            'b.join_company',
            'b.expbank',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.certificate_name',
            'f.name AS parent_name',
            'g.sum_practical_goal',
            'g.sum_goal',
        ]);
        $query->from('el_potential AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
        $query->leftJoin('el_cert AS e', 'e.certificate_code', '=', 'b.certificate_code');
        $query->leftJoin('el_unit AS f', 'f.code', '=', 'd.parent_code');
        $query->leftJoin('el_capabilities_review AS g', function ($subquery) {
            $subquery->on('g.user_id', '=', 'b.user_id')
                ->whereIn('g.id', function ($subquery2) {
                    $subquery2->select(['id'])
                        ->from('el_capabilities_review')
                        ->whereColumn('user_id', '=', 'g.user_id')
                        ->orderBy('id', 'desc')
                        ->limit(1);
                });
        });

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }
        if ($title) {
            $query->where('c.id', '=', $title);
        }
        if ($unit) {
            $query->where('d.id', '=',  $unit);
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($cert) {
            $query->whereIn('e.id', explode(',', $cert));
        }
        if ($from_percent) {
            $query->where('a.ratio', '>=', $from_percent);
        }
        if ($to_percent) {
            $query->where('a.ratio', '<=', $to_percent);
        }
        if ($from_year) {
            $query->where(\DB::raw('CONVERT(float, expbank)'), '>=', (float)$from_year);
        }
        if ($to_year) {
            $query->where(\DB::raw('CONVERT(float, expbank)'), '<=', (float)$to_year);
        }
        if ($start_date) {
            $query->where(\DB::raw('ROUND(CONVERT(float, DATEDIFF(day, join_company, GETDATE()))/365, 2)'), '>=', floatval($start_date));
        }
        if ($end_date) {
            $query->where(\DB::raw('ROUND(CONVERT(float, DATEDIFF(day, join_company, GETDATE()))/365, 2)'), '<=', floatval($end_date));
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $join_company = get_date($row->join_company, 'Y-m-d');
            $now = date('Y-m-d');
            $row->join_company = $row->join_company ? cal_date($join_company, $now) : '';

            $row->edit_url = route('module.potential.edit', ['id' => $row->id]);
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->name = $row->lastname . ' ' . $row->firstname;

            $row->count_complete = $this->countCourseCompleteUserTrainingRoadmap($row->user_id);
            $row->count_course = $this->countCourseRequired($row->user_id);
            $row->check = ($row->count_complete > 0 && $row->count_complete == $row->count_course) ? 1 : 0;

            $row->course = route('module.potential.course', ['user_id' => $row->user_id]);
            $row->export = route('module.potential.export_course', ['user_id' => $row->user_id]);

            if($row->sum_practical_goal){
                $row->ratio = number_format(($row->sum_practical_goal / $row->sum_goal)*100, 0) . ' %';

                $percent = CapabilitiesGroupPercent::where('from_percent', '<', (float) $row->ratio)
                    ->where('to_percent', '>', (float) $row->ratio)->first();

                $row->group_percent = ($percent) ? $percent->percent_group : '';
            }

            $user_code = $row->code;
            $row->d1 = '';
            $row->d2 = '';
            $row->d3 = '';

            $max_year = KPI::whereIn('year', function ($subquery) use ($user_code) {
                $subquery->select(\DB::raw('MAX(year)'))
                    ->from('el_kpi')
                    ->where('user_code', '=', $user_code);
            });
            if ($max_year->exists()) {
                $max_year = $max_year->first()->year;

                $year1 = KPI::getKpi($user_code, $max_year);
                if ($year1) {
                    $year2 = KPI::getKpi($user_code, $max_year - 1);
                    if ($year1->quarter_4) {
                        $row->d1 = 'Quý 4/' . $year1->year .' - '. $year1->quarter_4;
                        $row->d2 = 'Quý 3/' . $year1->year .' - '. $year1->quarter_3;
                        $row->d3 = 'Quý 2/' . $year1->year .' - '. $year1->quarter_2;
                    } else if ($year1->quarter_3) {
                        $row->d1 = 'Quý 3/' . $year1->year .' - '. $year1->quarter_3;
                        $row->d2 = 'Quý 2/' . $year1->year .' - '. $year1->quarter_2;
                        $row->d3 = 'Quý 1/' . $year1->year .' - '. $year1->quarter_1;
                    } else if ($year1->quarter_2) {
                        $row->d1 = 'Quý 2/' . $year1->year .' - '. $year1->quarter_2;
                        $row->d2 = 'Quý 1/' . $year1->year .' - '. $year1->quarter_1;
                        $row->d3 = 'Quý 4/' . $year2->year .' - '. $year2->quarter_4;
                    } else if ($year1->quarter_1) {
                        $row->d1 = 'Quý 1/' . $year1->year .' - '. $year1->quarter_1;
                        $row->d2 = 'Quý 4/' . $year1->year .' - '. $year1->quarter_4;
                        $row->d3 = 'Quý 3/' . $year2->year .' - '. $year2->quarter_3;
                    }
                }
            }

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function save(Request $request) {
        $this->validateRequest([
            'user_id' => 'required|exists:el_profile,user_id',
            'start_date' => 'required',
            'end_date' => 'required',
        ], $request, Potential::getAttributeName());

        $start_date = date_convert($request->input('start_date'));
        $end_date = date_convert($request->input('end_date'), '23:59:59');
        $user_id = $request->input('user_id');

        $profile = Profile::where('user_id', '=', $user_id)->first();

        $exists1 = Potential::where('user_id', '=', $user_id)
            ->where('start_date', '<=', $start_date)
            ->where('end_date' , '>=', $start_date)
            ->where('id' , '!=', $request->id)
            ->exists();

        $exists2 = Potential::where('user_id', '=', $user_id)
            ->where('start_date', '<=', $end_date)
            ->where('end_date' , '>=', $end_date)
            ->where('id' , '!=', $request->id)
            ->exists();

        if($exists1 || $exists2){
            json_message('Thời gian của ' . $profile->lastname . ' ' . $profile->firstname . ' đã tồn tại', 'error');
        }

        $model = Potential::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $model->start_date = date_convert($request->input('start_date'));
        $model->end_date = date_convert($request->input('end_date'), '23:59:59');

        if($model->start_date > $model->end_date){
            json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
        }

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.potential.index')
            ]);
        }
        json_message(trans('lageneral.save_error'), 'error');
    }
    public function form($id = 0) {
        if ($id) {
            $model = Potential::find($id);
            $profile = Profile::findOrFail($model->user_id);
            $page_title = $profile->lastname .' '. $profile->firstname;
            return view('potential::backend.potential.form', [
                'model' => $model,
                'page_title' => $page_title,
                'profile' => $profile,
            ]);
        }
        $model =  new Potential();
        $page_title = trans('backend.add_new') ;

        return view('potential::backend.potential.form', [
            'model' => $model,
            'page_title' =>$page_title,
        ]);
    }
    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        Potential::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new PotentialImports();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.potential.index'),
        ]);
    }
    public function listKPI()
    {
        $errors = session()->get('errors');
        \Session::forget('errors');

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        return view('potential::backend.potential_kpi.index', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
        ]);
    }
    public function getDataKPI(Request $request) {
        $search = $request->input('search');
        $unit = $request->input('unit');
        $title = $request->input('title');
        $year = $request->input('year');
        $quarter_1 = $request->input('quarter_1');
        $quarter_2 = $request->input('quarter_2');
        $quarter_3 = $request->input('quarter_3');
        $quarter_4 = $request->input('quarter_4');

        $sort = $request->input('sort', 'year');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = KPI::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.code',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.name AS parent_name'
        ]);
        $query->from('el_kpi AS a');
        $query->leftJoin('el_profile AS b', 'b.code', '=', 'a.user_code');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'd.parent_code');

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }

        if ($unit) {
            $unit = Unit::where('id', '=', $unit)->first();
            $query->where('b.unit_code', '=', $unit->code);
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('b.title_code', '=', $title->code);
        }

        if ($year){
            $query->where('a.year', '=', $year);
        }

        if ($quarter_1){
            $query->where('a.quarter_1', '=', $quarter_1);
        }
        if ($quarter_2){
            $query->where('a.quarter_2', '=', $quarter_2);
        }
        if ($quarter_3){
            $query->where('a.quarter_3', '=', $quarter_3);
        }
        if ($quarter_4){
            $query->where('a.quarter_4', '=', $quarter_4);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function importKPI(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new KPIImports();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.potential.kpi.list_kpi'),
        ]);
    }
    public function search()
    {
        $kpi = KPI::get();
        $cert = Certificate::get();
        $percent = CapabilitiesGroupPercent::get();

        return view('potential::backend.potential.search', [
            'kpi' => $kpi,
            'cert' => $cert,
            'percent' => $percent,
        ]);
    }
    public function getDataSearch(Request $request) {
        $cert = $request->cert;

        $from_percent = $request->input('from_percent');
        $to_percent = $request->input('to_percent');

        $from_year = $request->input('from_year');
        $to_year = $request->input('to_year');

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = \DB::query();
        $query->select([
            'a.*',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.certificate_name',
            'f.sum_practical_goal',
            'f.sum_goal',
            'g.name AS parent_name',
        ]);
        $query->from('el_profile AS a');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'a.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'a.unit_code');
        $query->leftJoin('el_cert AS e', 'e.certificate_code', '=', 'a.certificate_code');
        $query->leftJoin('el_capabilities_review AS f', function ($subquery) {
            $subquery->on('f.user_id', '=', 'a.user_id')
                ->whereIn('f.id', function ($subquery2) {
                    $subquery2->select(['id'])
                        ->from('el_capabilities_review')
                        ->whereColumn('user_id', '=', 'f.user_id')
                        ->orderBy('id', 'desc')
                        ->limit(1);
                });
        });
        $query->leftJoin('el_unit AS g', 'g.code', '=', 'd.parent_code');
        $query->where('a.user_id', '>', 2);

        if ($cert) {
            $query->whereIn('e.id', explode(',', $cert));
        }

        if ($to_percent) {
            $query->where(\DB::raw('ISNULL((sum_practical_goal / sum_goal) * 100, 0)'), '<=', (float)$to_percent);
        }

        if ($from_percent) {
            $query->where(\DB::raw('ISNULL((sum_practical_goal / sum_goal) * 100, 0)'), '>=', (float)$from_percent);
        }

        if ($from_year) {
            $query->where(\DB::raw('CONVERT(float, expbank)'), '>=', (float)$from_year);
        }

        if ($to_year) {
            $query->where(\DB::raw('CONVERT(float, expbank)'), '<=', (float)$to_year);
        }

        if ($start_date) {
            $query->where(\DB::raw('ROUND(CONVERT(float, DATEDIFF(day, join_company, GETDATE()))/365, 2)'), '>=', floatval($start_date));
        }

        if ($end_date) {
            $query->where(\DB::raw('ROUND(CONVERT(float, DATEDIFF(day, join_company, GETDATE()))/365, 2)'), '<=', floatval($end_date));
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach($rows as $row){

            if($row->sum_practical_goal){
                $row->ratio = number_format(($row->sum_practical_goal / $row->sum_goal)*100, 0) . ' %';

                $percent = CapabilitiesGroupPercent::where('from_percent', '<', (float) $row->ratio)
                ->where('to_percent', '>', (float) $row->ratio)->first();

                $row->group_percent = ($percent) ? $percent->percent_group : '';
            }

            $join_company = get_date($row->join_company, 'Y-m-d H:i:s');
            $now = date('Y-m-d H:i:s');
            $row->join_company = $row->join_company ? cal_date($join_company, $now) : '';

            $user_code = $row->code;
            $row->d1 = '';
            $row->d2 = '';
            $row->d3 = '';

            $max_year = KPI::whereIn('year', function ($subquery) use ($user_code) {
                $subquery->select(\DB::raw('MAX(year)'))
                    ->from('el_kpi')
                    ->where('user_code', '=', $user_code);
            });
            if ($max_year->exists()) {
                $max_year = $max_year->first()->year;

                $year1 = KPI::getKpi($user_code, $max_year);
                if ($year1) {
                    $year2 = KPI::getKpi($user_code, $max_year - 1);
                    if ($year1->quarter_4) {
                        $row->d1 = 'Quý 4/' . $year1->year .' - '. $year1->quarter_4;
                        $row->d2 = 'Quý 3/' . $year1->year .' - '. $year1->quarter_3;
                        $row->d3 = 'Quý 2/' . $year1->year .' - '. $year1->quarter_2;
                    } else if ($year1->quarter_3) {
                        $row->d1 = 'Quý 3/' . $year1->year .' - '. $year1->quarter_3;
                        $row->d2 = 'Quý 2/' . $year1->year .' - '. $year1->quarter_2;
                        $row->d3 = 'Quý 1/' . $year1->year .' - '. $year1->quarter_1;
                    } else if ($year1->quarter_2) {
                        $row->d1 = 'Quý 2/' . $year1->year .' - '. $year1->quarter_2;
                        $row->d2 = 'Quý 1/' . $year1->year .' - '. $year1->quarter_1;
                        $row->d3 = 'Quý 4/' . $year2->year .' - '. $year2->quarter_4;
                    } else if ($year1->quarter_1) {
                        $row->d1 = 'Quý 1/' . $year1->year .' - '. $year1->quarter_1;
                        $row->d2 = 'Quý 4/' . $year1->year .' - '. $year1->quarter_4;
                        $row->d3 = 'Quý 3/' . $year2->year .' - '. $year2->quarter_3;
                    }
                }
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function countCourseCompleteUserTrainingRoadmap($user_id) {
        $profile = Profile::find($user_id);
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $title_id = empty($title) ? null : $title->id;

        $query = Subject::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_subject AS a');
        $query->whereIn('a.id', function($subquery) use ($title_id){
            $subquery->select(['subject_id'])
                ->from('el_potential_roadmap')
                ->where('title_id', '=', $title_id);
        });
        $query->where(function ($subquery)  use ($user_id) {
            $subquery->orWhereIn('a.id', function ($subquery2) use ($user_id) {
                $subquery2->select(['el_online_course.subject_id'])
                    ->from('el_online_register')
                    ->join('el_online_course', 'el_online_course.id', '=', 'el_online_register.course_id')
                    ->join('el_online_course_complete', 'el_online_course_complete.course_id', '=', 'el_online_register.course_id')
                    ->where('el_online_course_complete.user_id', '=', $user_id);
            });
        });
        $query->orWhere(function ($subquery)  use ($user_id) {
            $subquery->orWhereIn('a.id', function ($subquery2) use ($user_id) {
                $subquery2->select(['el_offline_course.subject_id'])
                    ->from('el_offline_register')
                    ->join('el_offline_course', 'el_offline_course.id', '=', 'el_offline_register.course_id')
                    ->join('el_offline_course_complete', 'el_offline_course_complete.course_id', '=', 'el_offline_register.course_id')
                    ->where('el_offline_course_complete.user_id', '=', $user_id);
            });
        });
        return $query->count();
    }

    public function countCourseRequired($user_id) {
        $profile = Profile::find($user_id);
        $title = Titles::where('code', '=', $profile->title_code)->first();
        $title_id = empty($title) ? null : $title->id;

        $query = Subject::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_subject AS a');
        $query->whereIn('a.id', function($subquery) use ($title_id){
            $subquery->select(['subject_id'])
                ->from('el_potential_roadmap')
                ->where('title_id', '=', $title_id);
        });

        return $query->count();
    }

    public function exportSearch(Request $request)
    {
        $cert = $request->cert;
        $from_percent = $request->from_percent;
        $to_percent = $request->to_percent;
        $from_year = $request->from_year;
        $to_year = $request->to_year;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return (new PotentialSearchExport($cert, $from_percent, $to_percent, $from_year, $to_year, $start_date, $end_date))->download('tim_kiem_nhan_su_tiem_nang_'. date('d_m_Y') .'.xlsx');
    }

    public function export(Request $request)
    {
        $cert = $request->cert;
        $from_percent = $request->from_percent;
        $to_percent = $request->to_percent;
        $from_year = $request->from_year;
        $to_year = $request->to_year;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        return (new PotentialExport($cert, $from_percent, $to_percent, $from_year, $to_year, $start_date, $end_date))->download('danh_sach_nhan_su_tiem_nang_'. date('d_m_Y') .'.xlsx');
    }

    public function exportCourse($user_id)
    {
        return (new ExportCourse($user_id))->download('danh_sach_khoa_hoc_nhan_su_tiem_nang_'. date('d_m_Y') .'.xlsx');
    }
}
