<?php

namespace App\Http\Controllers\Backend;

use App\EmulationProgram;
use App\EmulationProgramObject;
use App\EmulationProgramCondition;
use App\ArmorialEmulationProgram;
use App\EmulationUserGetArmorial;
use App\Imports\EmulationUserImport;
use App\Profile;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\EmulationProgramExport;
use App\Models\Categories\Titles;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use App\Models\Categories\Area;
use App\EmulationPromotion;
use Carbon\Carbon;

class EmulationProgramController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('backend.emulation_program.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $time_start = $request->input('time_start');
        $time_end = $request->input('time_end');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = EmulationProgram::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_emulation_program as a');

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('a.name', 'like', '%' . $search . '%');
                $subquery->orWhere('a.code', 'like', '%' . $search . '%');
            });
        }

        if ($time_start) {
            $query->where('a.time_start', '>=', date_convert($time_start));
        }

        if ($time_end) {
            $query->where('a.time_start', '<=', date_convert($time_end, '23:59:59'));
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->result_emulation = route('backend.emulation_program.result_emulation', ['id' => $row->id]);
            $row->edit_url = route('backend.emulation_program.edit', ['id' => $row->id]);
            $row->time_start = get_date($row->time_start);
            $row->time_end = get_date($row->time_end);
            $row->image = image_file($row->image);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        if ($id) {
            $model = EmulationProgram::find($id);
            $page_title = $model->name;
            $amorial_emulations = ArmorialEmulationProgram::where('emulation_id',$id)->get();
            $titles = Titles::where('status', '=', 1)->get();
            $get_courses_online = OnlineCourse::where('status',1)->where('isopen',1)->get();
            $get_courses_offline = OfflineCourse::where('status',1)->where('isopen',1)->get();
            $get_quizs = Quiz::where('status',1)->where('is_open',1)->get();

            return view('backend.emulation_program.form', [
                'model' => $model,
                'page_title' => $page_title,
                'amorial_emulations' => $amorial_emulations,
                'titles' => $titles,
                'get_courses_online' => $get_courses_online,
                'get_courses_offline' => $get_courses_offline,
                'get_quizs' => $get_quizs,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }

        $model = new EmulationProgram();
        $page_title = trans('backend.add_new');

        return view('backend.emulation_program.form', [
            'model' => $model,
            'page_title' => $page_title,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'code' => 'required',
            'description' => 'required',
            'time_start'=>'required',
            'image' => 'nullable|string',
            'time_end' => 'required',
        ], $request, EmulationProgram::getAttributeName());
        $model = EmulationProgram::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->time_start = date_convert($request->input('time_start'));
        $model->time_end = $request->input('time_end') ? date_convert($request->input('time_end'), '23:59:59') : null;

        if ($request->image) {
            $sizes = config('image.sizes.news');
            $model->image = upload_image($sizes, $request->image);
        }

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.emulation_program.edit',['id'=>$model->id]),
            ]);
        }

        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        EmulationProgram::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
    public function approve(Request $request)
    {
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        foreach ($ids as $id) {
            $query = EmulationProgram::query();
            $query->where('id', $id);
            $query->update(['status' => $status]);
        }
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = EmulationProgram::findOrFail($id);
                $model->isopen = $status;
                $model->save();
            }
        } else {
            $model = EmulationProgram::findOrFail($ids);
            $model->isopen = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }

    public function export()
    {
        return (new EmulationProgramExport())->download('danh_sach_chuong_trinh_thi_dua'. date('d_m_Y') .'.xlsx');
    }

    public function resultEmulation($emulation_id) {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $max_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };
        $emulation_program = EmulationProgram::find($emulation_id);
        return view('backend.emulation_program.emulation_result',[
            'emulation_program' => $emulation_program,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'max_area' => $max_area,
            'level_name_area' => $level_name_area,
        ]);
    }

    public function getDataResultEmulation($emulation_id, Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');
        $area = $request->input('area');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $get_emulation_objects_unit = EmulationProgramObject::where('emulation_id',$emulation_id)->pluck('unit_id');
        $get_emulation_objects_title = EmulationProgramObject::where('emulation_id',$emulation_id)->pluck('title_code');
        $get_quiz_condition = EmulationProgramCondition::where('emulation_id',$emulation_id)->where('type',3)->get();

        if (!$get_quiz_condition->isEmpty()) {
            $get_sum_quizs = EmulationProgramCondition::query()
            ->selectRaw("user_id, SUM(point) as sum_point")
            ->from('el_emulation_program_condition as epc')
            ->leftjoin('el_emulation_promotion as ep', function ($join){
                $join->on('epc.quiz_id', '=', 'ep.course_id')
                    ->on('epc.type', '=', 'ep.type');     
            })
            ->groupby('user_id')
            ->where('epc.emulation_id', $emulation_id)
            ->get();
        }

        $model = EmulationProgramCondition::query()
        ->selectRaw("user_id, SUM(point) as sum_point")
        ->from('el_emulation_program_condition as epc')
        ->leftjoin('el_emulation_promotion as ep', function ($join){
            $join->on('epc.course_id', '=', 'ep.course_id')
                 ->on('epc.type', '=', 'ep.type');     
        })
        ->groupby('user_id')
        ->where('epc.emulation_id', $emulation_id);

        $query = Profile::query()
        ->select(['p.*',
                'b.sum_point',
                'p.id',
                'p.user_id',
                'p.code',
                'p.email',
                'p.firstname',
                'p.lastname',
                'p.status',
                'b.name AS unit_name',
                'c.name AS title_name',
                'd.name AS area_name',
                'e.name AS unit_manager',
        ])
        ->from('el_profile as p')
        ->joinSub($model,'b', function ($join){
            $join->on('b.user_id', '=', 'p.user_id');
        })
        ->leftJoin('el_unit AS b', 'b.id', '=', 'p.unit_id')
        ->leftJoin('el_unit AS e', 'e.code', '=', 'b.parent_code')
        ->leftJoin('el_titles AS c', 'c.code', '=', 'p.title_code')
        ->leftJoin('el_area AS d', 'd.code', '=', 'p.area_code')
        ->where('p.user_id', '>', 2);

        if (!$get_emulation_objects_unit->isEmpty() || !$get_emulation_objects_title->isEmpty()) {
            $query->where(function ($sub_query) use ($get_emulation_objects_unit, $get_emulation_objects_title) {
                $sub_query->WhereIn('b.id', $get_emulation_objects_unit);
                $sub_query->orWhereIn('c.code', $get_emulation_objects_title);
            });
        }
        
        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('p.code', 'like', '%'. $search .'%');
            });
        }
        if ($request->area) {
            $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if (!is_null($status)) {
            $query->where('p.status', '=', $status);
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
            $query->where('p.title_code', '=', $title->code);
        }

        $count = $query->count();
        $query->orderBy('p.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->armorial_images = '';
            $get_armorials = ArmorialEmulationProgram::where('emulation_id',$emulation_id)->get();
            if (!$get_quiz_condition->isEmpty()) {
                foreach ($get_sum_quizs as $key => $get_sum_quiz) {
                    if ($get_sum_quiz->user_id == $row->user_id) {
                        $row->sum_point = $row->sum_point + $get_sum_quiz->sum_point;
                        foreach ($get_armorials as $key => $get_armorial) {
                            if ($row->sum_point > $get_armorial->min_score && $row->sum_point < $get_armorial->max_score) {
                                $row->armorial = $get_armorial->name;
                                $row->armorial_images = image_file($get_armorial->images);
                                EmulationUserGetArmorial::createUserGetArmorial($emulation_id, $get_armorial->id, $row->user_id);
                            } else if($row->sum_point > $get_armorial->max_score) {
                                EmulationUserGetArmorial::deleteUserGetArmorial($emulation_id, $get_armorial->id, $row->user_id);
                            }
                        }
                    }
                }
            } else {
                foreach ($get_armorials as $key => $get_armorial) {
                    if ($row->sum_point > $get_armorial->min_score && $row->sum_point < $get_armorial->max_score) {
                        $row->armorial = $get_armorial->name;
                        $row->armorial_images = image_file($get_armorial->images);
                        EmulationUserGetArmorial::createUserGetArmorial($emulation_id, $get_armorial->id, $row->user_id);
                    } else if($row->sum_point > $get_armorial->max_score) {
                        EmulationUserGetArmorial::deleteUserGetArmorial($emulation_id, $get_armorial->id, $row->user_id);
                    }
                }
            }
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    // HUY HIỆU
    public function saveArmorial($emulation_id, Request $request)
    {
        $this->validateRequest([
            'name_armorials' => 'required',
            'min_scores' => 'required',
            'description_armorials' => 'required',
            'max_scores'=>'required',
            'image_armorials'=>'required',
        ], $request, ArmorialEmulationProgram::getAttributeName());

        $name_armorials = $request->name_armorials;
        $code_armorials = $request->code_armorials;
        $min_scores = $request->min_scores;
        $max_scores = $request->max_scores;
        $description_armorials = $request->description_armorials;
        $image_armorials = $request->image_armorials;
        foreach ($code_armorials as $key => $code_armorial) {
            $model = ArmorialEmulationProgram::firstOrNew(['emulation_id'=>$emulation_id, 'code'=>$code_armorial]);

            $sizes = config('image.sizes.amorial');
            $model->images = upload_image($sizes, $image_armorials[$key]);

            $model->code = $code_armorial;
            $model->name = $name_armorials[$key];
            $model->min_score = $min_scores[$key];
            $model->max_score = $max_scores[$key];
            $model->description = $description_armorials[$key];
            $model->emulation_id = $emulation_id;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
            'redirect' => route('backend.emulation_program.edit',['id'=>$emulation_id,'tabs'=>'armorial']),
        ]);
    }

    // ĐỐI TƯỢNG
    public function saveObject($emulation_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
            'title_code' => 'nullable',
        ], $request);

        $title_code = $request->input('title_code');
        $unit_id = $request->input('unit_id');
        $status_unit = $request->input('status_unit');
        $status_title = $request->input('status_title');
        if ($unit_id) {
            foreach ($unit_id as $item){
                $model = EmulationProgramObject::firstOrNew(['unit_id' => $unit_id, 'emulation_id' => $emulation_id]);
                $model->emulation_id = $emulation_id;
                $model->unit_id = $item;
                $model->save();
            }
            if($model->save()) {
                json_result([
                    'status' => 'success',
                    'message' => 'Thêm đơn vị thành công',
                    'redirect' => route('backend.emulation_program.edit',['id'=>$emulation_id,'tabs'=>'object']),
                ]);
            }
        }else{
            foreach ($title_code as $item){
                $model = EmulationProgramObject::firstOrNew(['title_code' => $title_code, 'emulation_id' => $emulation_id]);
                $model->emulation_id = $emulation_id;
                $model->title_code = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm chức danh thành công',
                'redirect' => route('backend.emulation_program.edit',['id'=>$emulation_id,'tabs'=>'object']),
            ]);
        }
    }

    public function getUserObject($emulation_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = EmulationProgramObject::query();
        $query->select([
            'a.*', 
            'b.code AS profile_code', 
            'b.lastname', 
            'b.firstname', 
            'b.email', 
            'c.name AS title_name', 
            'd.name AS unit_name', 
            'e.name AS unit_manager', 
        ]);
        $query->from('el_emulation_program_object AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'd.parent_code');
        $query->where('a.emulation_id', '=', $emulation_id);
        $query->whereNull('a.title_code');
        $query->whereNull('a.unit_id');

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->profile_name = $row->lastname . ' ' . $row->firstname;
            $row->parent = $row->parent_name;   
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getObject($emulation_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = EmulationProgramObject::query();
        $query->select(['a.*', 'b.name AS title_name', 'c.name AS unit_name']);
        $query->from('el_emulation_program_object AS a');
        $query->leftJoin('el_titles AS b', 'b.code', '=', 'a.title_code');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->where('a.emulation_id', '=', $emulation_id);
        $query->whereNull('a.user_id');

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->unit = $row->unit_name;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeObject($emulation_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Đối tượng',
        ]);

        $item = $request->input('ids');
        EmulationProgramObject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importObject($emulation_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new EmulationUserImport($emulation_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('backend.emulation_program.edit',['id'=>$emulation_id,'tabs'=>'object']),
        ]);
    }

    // ĐIỀU KIỆN
    public function saveCondition($emulation_id, Request $request){
        $this->validateRequest([
            'courses_online' => 'required_if:condition_type,1|',
            'courses_offline' => 'required_if:condition_type,2|',
            'quizs' => 'required_if:condition_type,3|',
        ], $request);

        $courses_online = $request->input('courses_online');
        $courses_offline = $request->input('courses_offline');
        $quizs = $request->input('quizs');
        $status_title = $request->input('status_title');

        if ($courses_offline) {
            foreach ($courses_offline as $item){
                if (EmulationProgramCondition::checkConditionCourse($emulation_id, $item, 2)){
                    continue;
                }
                $model = new EmulationProgramCondition();
                $model->emulation_id = $emulation_id;
                $model->course_id = $item;
                $model->type = 2;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm Khóa học tập trung thành công',
            ]);
        } else if ($quizs) {
            foreach ($quizs as $item){
                if (EmulationProgramCondition::checkConditionCourse($emulation_id, $item, 3)){
                    continue;
                }
                $model = new EmulationProgramCondition();
                $model->emulation_id = $emulation_id;
                $model->quiz_id = $item;
                $model->type = 3;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm kỳ thi thành công',
            ]);
        } else {
            foreach ($courses_online as $item){
                if (EmulationProgramCondition::checkConditionCourse($emulation_id, $item, 1)){
                    continue;
                }
                $model = new EmulationProgramCondition();
                $model->emulation_id = $emulation_id;
                $model->course_id = $item;
                $model->type = 1;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => 'Thêm khóa học online thành công',
            ]);
        }
    }

    public function getQuiz($emulation_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Quiz::query();
        $query->select([
            'a.*', 
        ]);
        $query->from('el_quiz AS a');
        $query->leftJoin('el_emulation_program_condition AS b', 'a.id', '=', 'b.quiz_id');
        $query->where('b.emulation_id', '=', $emulation_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $key => $row) {
            $max_time = QuizPart::where('quiz_id',$row->id)->max('end_date');
            $min_time = QuizPart::where('quiz_id',$row->id)->min('start_date');
            $row->start_date = Carbon::parse($min_time)->format('d-m-Y');
            $row->end_date = Carbon::parse($max_time)->format('d-m-Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getCourse($emulation_id, $type, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = EmulationProgramCondition::query();
        $query->select(['a.*','b.name as course_name','b.code','b.start_date','b.end_date']);
        $query->from('el_emulation_program_condition AS a');
        if ($type == 1) {
            $query->Join('el_online_course AS b', 'b.id', '=', 'a.course_id');
            $query->where('a.type', '=', 1);
        } else {
            $query->Join('el_offline_course AS b', 'b.id', '=', 'a.course_id');
            $query->where('a.type', '=', 2);
        }
        $query->where('a.emulation_id', '=', $emulation_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $key => $row) {
            $row->start_date = Carbon::parse($row->start_date)->format('d-m-Y');
            $row->end_date = $row->end_date ? Carbon::parse($row->end_date)->format('d-m-Y') : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeConditon($emulation_id, $type, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Đối tượng',
        ]);

        $item = $request->input('ids');
        EmulationProgramCondition::where('type',$type)->delete($item);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
