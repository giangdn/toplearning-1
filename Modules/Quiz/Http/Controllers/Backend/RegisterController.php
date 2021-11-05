<?php

namespace Modules\Quiz\Http\Controllers\Backend;

use App\Automail;
use App\MailSignature;
use App\Profile;
use App\Models\Categories\UnitManager;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\ProfileView;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSettingAlert;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Exports\RegisterExport;
use Modules\Quiz\Exports\RegisterSecondaryExport;
use Modules\Quiz\Imports\RegisterImport;

class RegisterController extends Controller
{
    public function index($quiz_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $errors = session()->get('errors');
        \Session::forget('errors');

        $profile = Profile::find(\Auth::id());
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz_name = Quiz::findOrFail($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->get();

        return view('quiz::backend.register.index', [
            'quiz_name' => $quiz_name,
            'quiz_id' => $quiz_id,
            'quiz_part' => $quiz_part,
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'unit' => $unit,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData($quiz_id, Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $title = $request->input('title');
        $unit = $request->input('unit');
        $part = $request->input('part');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        QuizRegister::addGlobalScope(new DraftScope());
        $query = QuizRegister::query();
        $query->select([
            'el_quiz_register.*',
            'el_profile.lastname',
            'el_profile.firstname',
            'el_profile.code',
            'el_profile.email',
            'el_profile.title_code',
            'el_profile.unit_code',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.name as part_name',
            'e.start_date as part_start_date',
            'e.end_date as part_end_date',
            'f.name AS parent_name',
            'u.username',
        ]);
        $query->join('el_profile', 'el_profile.user_id', '=', 'el_quiz_register.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'el_profile.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'el_profile.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
        $query->leftJoin('el_quiz_part AS e', 'e.id', '=', 'el_quiz_register.part_id');
        $query->leftJoin('el_unit AS f', 'f.code', '=', 'd.parent_code');
        $query->leftJoin('user AS u', 'u.id', '=', 'el_profile.user_id');
        $query->where('el_quiz_register.quiz_id', '=', $quiz_id);
        $query->where('el_quiz_register.type', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_profile.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('u.username', 'like', '%'. $search .'%');
            });
        }
        if (!is_null($status)) {
            $query->where('el_profile.status', '=', $status);
        }
        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('el_profile.title_code', '=', $title->code);
        }
        if ($unit) {
            $unit = Unit::where('id', '=', $unit)->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('el_profile.unit_code', $unit_id);
                $sub_query->orWhere('d.id', '=', $unit->id);
            });
        }
        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($part) {
            $query->where('el_quiz_register.part_id', '=',  $part);
        }

        $count = $query->count();
        $query->orderBy('el_quiz_register.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->part_start_date = get_date($row->part_start_date, 'H:i d/m/Y');
            $row->part_end_date = get_date($row->part_end_date, 'H:i d/m/Y');
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataNotRegister($quiz_id, Request $request){
        $search = $request->input('search');
        $title = $request->input('title');
        $status = $request->input('status');
        $unit = $request->input('unit');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        Profile::addGlobalScope(new DraftScope('user_id'));
        $query = Profile::query();//dd($query->toSql());
        $query->select([
            'el_profile.*',
            'b.name AS title_name',
            'c.name AS unit_name',
            'd.name AS parent_name'
        ]);
        $query->leftJoin('el_titles AS b', 'b.code', '=', 'el_profile.title_code');
        $query->leftJoin('el_unit AS c', 'c.code', '=', 'el_profile.unit_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->whereNotExists(function($sub_query) use ($quiz_id) {
            $sub_query->select(['id'])
                ->from('el_quiz_register')
                ->where('quiz_id', '=', $quiz_id)
                ->where('type', '=', 1)
                ->whereColumn('user_id', '=', 'el_profile.user_id');
        });

        $query->where('el_profile.user_id', '>', 2);
        $query->where('el_profile.type_user', '=', 1);

//        if ($managers) {
//            $query->whereIn('c.id', $managers);
//        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile.code', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('el_profile.status', '=', $status);
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('el_profile.title_code', '=', $title->code);
        }

        if ($unit) {
            $unit = Unit::where('id', '=', $unit)->first();
            $query->where('el_profile.unit_code', '=', $unit->code);
        }

        $count = $query->count();
        $query->orderBy('el_profile.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit_name = '';
            }else{
                $row->parent = $row->parent_name;
            }
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function form($quiz_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $profile = Profile::find(\Auth::id());
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz_name = Quiz::findOrFail($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->get();
        return view('quiz::backend.register.form', [
            'quiz_id' => $quiz_id,
            'quiz_name' => $quiz_name,
            'quiz_part' => $quiz_part,
            'max_unit'=>$max_unit,
            'level_name'=>$level_name,
            'unit' => $unit,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function save($quiz_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'part_id' => 'required',
        ], $request, QuizRegister::getAttributeName());

        $part_id = $request->input('part_id');
        $ids = $request->input('ids', null);

        $quiz = Quiz::with('type')->find($quiz_id);
        $part = QuizPart::whereQuizId($quiz_id)->where('id', $part_id)->first();

        foreach($ids as $id){
            if (QuizRegister::checkExists($id, $quiz_id)) {
                continue;
            }else{
                $model = new QuizRegister();
                $model->user_id = $id;
                $model->quiz_id = $quiz_id;
                $model->part_id = $part_id;
                $model->type = 1;
                $model->save();

                if ($quiz->status == 1){
                    $profile = ProfileView::query()->where('user_id', $id)->first();
                    $signature = getMailSignature($profile->user_id);
                    $params = [
                        'signature' => $signature,
                        'gender' => ($profile->gender=='1'?'Anh':'Chị'),
                        'full_name' => $profile->full_name,
                        'quiz_name' => $quiz->name,
                        'quiz_type' => $quiz->type? $quiz->type->name:'',
                        'quiz_part_name' => $part->name,
                        'start_quiz_part' => get_datetime($part->start_date),
                        'end_quiz_part' => get_datetime($part->end_date),
                        'quiz_time' => $quiz->limit_time,
                        'pass_score' => $quiz->pass_score,
                        'url' => route('module.quiz.doquiz.index', ['quiz_id' => $quiz_id,'part_id'=>$part_id]),
                    ];
                    $user_id = [$id];
                    $this->saveEmailQuizRegister($params,$user_id,$part_id,1);
                }
            }
        }
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        foreach ($ids as $id){
            $register = QuizRegister::find($id);

            $result = QuizResult::where('quiz_id', '=', $register->quiz_id)
                ->where('user_id', '=', $register->user_id)
                ->where('type', '=', 1)
                ->first();

            if ($result){
               continue;
            }else{
                $register->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importRegister($quiz_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit');

        $import = new RegisterImport($quiz_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = $unit > 0 ? route('module.training_unit.quiz.register', ['id' => $quiz_id]) : route('module.quiz.register', ['id' => $quiz_id]);

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => $redirect,
        ]);
    }

    public function exportRegister($quiz_id){
        return (new RegisterExport($quiz_id))->download('nhan_vien_noi_bo_dang_ki_ky_thi_'. date('d_m_Y') .'.xlsx');
    }

    public function indexSecondary($quiz_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $errors = session()->get('errors');
        \Session::forget('errors');

        $profile = Profile::find(\Auth::id());
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz_name = Quiz::findOrFail($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->get();

        return view('quiz::backend.register.secondary_index', [
            'quiz_name' => $quiz_name,
            'quiz_id' => $quiz_id,
            'quiz_part' => $quiz_part,
            'unit' => $unit,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getDataSecondary($quiz_id, Request $request) {
        $search = $request->input('search');
        $part = $request->input('part');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        QuizRegister::addGlobalScope(new DraftScope());
        $query = QuizRegister::query();
        $query->select([
            'el_quiz_register.*',
            'b.name',
            'b.code',
            'b.dob',
            'b.email',
            'b.identity_card',
            'c.name as part_name',
            'c.start_date as part_start_date',
            'c.end_date as part_end_date'
        ]);
        $query->leftJoin('el_quiz_user_secondary AS b', 'b.id', '=', 'el_quiz_register.user_id');
        $query->leftJoin('el_quiz_part AS c', 'c.id', '=', 'el_quiz_register.part_id');
        $query->where('el_quiz_register.quiz_id', '=', $quiz_id);
        $query->where('el_quiz_register.type', '=', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.name', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }
        if ($part) {
            $query->where('c.id', '=',  $part);
        }

        $count = $query->count();
        $query->orderBy('el_quiz_register.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->part_start_date = get_date($row->part_start_date, 'H:i d/m/Y');
            $row->part_end_date = get_date($row->part_end_date, 'H:i d/m/Y');
            $row->dob = get_date($row->dob, 'd/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataNotUserSecondary($quiz_id, Request $request){
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        QuizUserSecondary::addGlobalScope(new DraftScope());
        $query = QuizUserSecondary::query();
        $query->select(['el_quiz_user_secondary.*']);
        $query->whereNotIn('el_quiz_user_secondary.id', function($sub_query) use ($quiz_id) {
            $sub_query->select(['user_id']);
            $sub_query->from('el_quiz_register');
            $sub_query->where('quiz_id', '=', $quiz_id);
            $sub_query->where('type', '=', 2);
        });

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_quiz_user_secondary.name', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_quiz_user_secondary.code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy('el_quiz_user_secondary.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->dob = get_date($row->dob, 'd/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function formSecondary($quiz_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $profile = Profile::find(\Auth::id());
        $unit = Unit::where('code', '=', $profile->unit_code)->first();

        $quiz_name = Quiz::findOrFail($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->get();

        return view('quiz::backend.register.secondary_form', [
            'quiz_id' => $quiz_id,
            'quiz_name' => $quiz_name,
            'quiz_part' => $quiz_part,
            'unit' => $unit,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function saveSecondary($quiz_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'part_id' => 'required',
        ], $request);

        $part_id = $request->input('part_id');
        $ids = $request->input('ids', null);

        $quiz = Quiz::with('type')->find($quiz_id);
        $part = QuizPart::whereQuizId($quiz_id)->where('id', $part_id)->first();
        foreach($ids as $id){
            if (QuizRegister::checkSecondaryExists($id, $quiz_id)) {
                continue;
            }else{
                $model = new QuizRegister();
                $model->user_id = $id;
                $model->quiz_id = $quiz_id;
                $model->part_id = $part_id;
                $model->type = 2;
                $model->save();

                if ($quiz->status == 1){
                    $signature = getMailSignature($id, 2);

                    $profile = QuizUserSecondary::find($id);
                    $params = [
                        'gender' => 'Anh/Chị',
                        'full_name' => $profile->name,
                        'quiz_name' => $quiz->name,
                        'quiz_type' => $quiz->type? $quiz->type->name:'',
                        'quiz_part_name' => $part->name,
                        'start_quiz_part' => get_datetime($part->start_date),
                        'end_quiz_part' => get_datetime($part->end_date),
                        'quiz_time' => $quiz->limit_time,
                        'pass_score' => $quiz->pass_score,
                        'url' => route('module.quiz.doquiz.index', ['quiz_id' => $quiz_id,'part_id'=>$part_id]),
                        'signature' => $signature,
                    ];
                    $user_id = [$id];
                    $this->saveEmailQuizRegister($params,$user_id,$part_id,2);
                }
            }
        }
    }

    public function removeSecondary(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $register = QuizRegister::find($id);

            $result = QuizResult::where('quiz_id', '=', $register->quiz_id)
                ->where('user_id', '=', $register->user_id)
                ->where('type', '=', 2)
                ->first();

            if ($result){
                continue;
            }else{
                $register->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importRegisterSecondary($quiz_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit');

        $import = new RegisterImport($quiz_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.quiz.register.user_secondary', ['id' => $quiz_id]);

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => $redirect,
        ]);
    }

    public function exportRegisterSecondary($quiz_id){
        return (new RegisterSecondaryExport($quiz_id))->download('thi_sinh_ben_ngoai_dang_ki_ky_thi_'. date('d_m_Y') .'.xlsx');
    }

    public function saveEmailQuizRegister(array $params,array $user_id,int $part_id, int $user_type)
    {
        $automail = new Automail();
        $automail->template_code = 'quiz_registerd';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->user_type = $user_type;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $part_id;
        $automail->object_type = 'approve_quiz';
        $automail->addToAutomail();
    }

    public function createNewSecondary($quiz_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $quiz_name = Quiz::findOrFail($quiz_id);
        $quiz_part = QuizPart::where('quiz_id', '=', $quiz_id)->get();

        return view('quiz::backend.register.secondary_new', [
            'quiz_id' => $quiz_id,
            'quiz_name' => $quiz_name,
            'quiz_part' => $quiz_part,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function saveNewSecondary($quiz_id, Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_quiz_user_secondary,code,'. $request->id,
            'name' => 'required',
            'username' => 'required_if:id,==,|min:6|max:32',
            'password' => 'nullable|min:8|max:32|required_if:id,==,',
            'repassword' => 'same:password',
            'email' => 'nullable|email',
            'identity_card' => 'required|min:9|max:14',
        ], $request, QuizUserSecondary::getAttributeName());

        $part_id = $request->input('part_id');
        $quiz = Quiz::with('type')->find($quiz_id);
        $part = QuizPart::whereQuizId($quiz_id)->where('id', $part_id)->first();

        if (empty($part)){
            json_message('Chưa chọn ca thi', 'error');
        }

        if (empty($request->id)){
            $setting_alert = QuizSettingAlert::query()->first();

            if ($setting_alert){
                $user_second = QuizUserSecondary::query()
                    ->where('identity_card', '=', $request->identity_card)
                    ->whereRaw(dateAddSql('created_at', $setting_alert->from_time, 'day') ." <= '". now() ."'")
                    ->whereRaw(dateAddSql('created_at', $setting_alert->to_time, 'day') ." >= '". now() ."'")
                    ->first();

                if ($user_second){
                    session()->put('errors', ['CMND '. $user_second->identity_card .' đã được thêm trước đó']);
                    session()->save();
                }
            }
        }

        $model = QuizUserSecondary::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->username = 'secondary_'. $model->username;
        if ($model->dob) {
            $model->dob = date_convert($model->dob);
        }
        $model->password = password_hash($request->input('password'), PASSWORD_DEFAULT);

        if ($model->save()) {

            $quiz_register = new QuizRegister();
            $quiz_register->user_id = $model->id;
            $quiz_register->quiz_id = $quiz_id;
            $quiz_register->part_id = $part_id;
            $quiz_register->type = 2;
            $quiz_register->save();

            if ($quiz->status == 1){
                $signature = getMailSignature($model->id, 2);
                $params = [
                    'gender' => 'Anh/Chị',
                    'full_name' => $model->name,
                    'quiz_name' => $quiz->name,
                    'quiz_type' => $quiz->type? $quiz->type->name:'',
                    'quiz_part_name' => $part->name,
                    'start_quiz_part' => get_datetime($part->start_date),
                    'end_quiz_part' => get_datetime($part->end_date),
                    'quiz_time' => $quiz->limit_time,
                    'pass_score' => $quiz->pass_score,
                    'url' => route('module.quiz.doquiz.index', ['quiz_id' => $quiz_id,'part_id'=>$part_id]),
                    'signature' => $signature,
                ];
                $user_id = [$model->id];
                $this->saveEmailQuizRegister($params,$user_id,$part_id,2);
            }

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.quiz.register.user_secondary', ['id' => $quiz_id])
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function sendMailUserRegisted($quiz_id, $type, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request,[
            'ids' => 'Học viên',
        ]);

        $ids = $request->input('ids', null);

        $quiz = Quiz::with('type')->find($quiz_id);
        $users = QuizRegister::query()->whereIn('id', $ids)->where('type', $type)->get();
        foreach($users as $user) {
            $part = QuizPart::whereQuizId($quiz_id)->where('id', $user->part_id)->first();

            if ($user->type == 1)
                $profile = ProfileView::query()->where('user_id', $user->user_id)->first();
            else
                $profile = QuizUserSecondary::query()->where('id',$user->user_id)->first();

            $signature = getMailSignature($user->user_id, $type);
            $params = [
                'signature' => $signature,
                'gender' => $user->type == 1 ? ($profile->gender=='1'?'Anh':'Chị') : 'Anh/Chị',
                'full_name' => $user->type == 1 ? $profile->full_name : $profile->name,
                'quiz_name' => $quiz->name,
                'quiz_type' => $quiz->type ? $quiz->type->name : '',
                'quiz_part_name' => $part->name,
                'start_quiz_part' => get_datetime($part->start_date),
                'end_quiz_part' => get_datetime($part->end_date),
                'quiz_time' => $quiz->limit_time,
                'pass_score' => $quiz->pass_score,
                'url' => route('module.quiz.doquiz.index', ['quiz_id' => $quiz_id, 'part_id' => $part->id]),
            ];
            $user_id = [$user->user_id];
            $this->saveEmailQuizRegister($params, $user_id, $part->id, $type);
        }

        json_message('Gửi mail thành công','success');
    }
}
