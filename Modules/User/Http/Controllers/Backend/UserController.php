<?php

namespace Modules\User\Http\Controllers\Backend;

use App\Imports\TrainingProgramLearnedImport;
use App\Imports\WorkingProcessImport;
use App\Models\Categories\Area;
use App\Certificate;
use App\Exports\UserExport;
use App\Imports\UserImport;
use App\Jobs\NotifyUserOfCompletedImportUser;
use App\Models\Categories\Position;
use App\Notifications;
use App\Permission;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\ProfileView;
use App\Scopes\DraftScope;
use App\User;
use App\UserMeta;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic;
use Modules\API\Entities\API;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use App\Http\Controllers\Controller;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\User\Entities\HistoryChangeInfo;
use Modules\User\Entities\TrainingProcess;
use App\Models\Categories\TrainingForm;

class UserController extends Controller
{
/*******************************/
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $notifications = Notifications::where('notifiable_id', '=', \Auth::id())
            ->where('notifiable_type', '=', 'App\User')
            ->whereNull('read_at')
            ->get();

        $max_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };
        return view('user::backend.user.index2', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'max_area' => $max_area,
            'level_name_area' => $level_name_area,
            'notifications' => $notifications,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');
        $area = $request->input('area');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $unit_manager = Permission::isUnitManager();

        if(!$unit_manager){
            ProfileView::addGlobalScope(new DraftScope('user_id'));
        }

        $query = ProfileView::query();
        $query->select([
            'el_profile_view.*'
        ]);
        $query->from('el_profile_view');
        $query->leftjoin('user as u','u.id','=','el_profile_view.user_id');
        $query->where('el_profile_view.user_id', '>', 2);
        $query->where('el_profile_view.type_user', '=', 1);

        if ($unit_manager){
            $unit_user = Unit::find(session('user_unit'));
            $child_arr = Unit::getArrayChild(@$unit_user->code);

            //$query->where('el_profile_view.user_id', '!=', Auth::id());
            $query->where(function ($sub) use ($unit_user, $child_arr){
                $sub->orWhere('el_profile_view.unit_id', '=', @$unit_user->id);
                $sub->orWhereIn('el_profile_view.unit_id', $child_arr);
            });
        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_profile_view.full_name', 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile_view.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile_view.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('u.username', 'like', '%'. $search .'%');
            });
        }
        if ($request->area) {
            $query->leftJoin('el_unit AS c', 'c.code', '=', 'el_profile_view.unit_code');
            $query->leftJoin('el_area AS area', 'area.id', '=', 'c.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if (!is_null($status)) {
            $query->where('el_profile_view.status_id', '=', $status);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('el_profile_view.unit_id', $unit_id);
                $sub_query->orWhere('el_profile_view.unit_id', '=', $unit->id);
            });
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('el_profile_view.title_code', '=', $title->code);
        }

        $count = $query->count();
        $query->orderBy('el_profile_view.status_id', 'desc');
        $query->orderBy('el_profile_view.code', 'desc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.backend.user.edit', ['id' => $row->user_id]);
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);
            $row->area_url = route('module.backend.user.get_area', ['user_id' => $row->user_id]);
            $row->avatar = Profile::avatar($row->id);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $max_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };

        $user_meta = function ($user_id, $key){
            return UserMeta::where('user_id', '=', $user_id)->where('key', '=', $key)->first(['value']);
        };
        $certs = Certificate::get();
        if ($id) {
            $model = Profile::where(['user_id'=>$id])->where('user_id','>',2)->firstOrFail();
            $user = User::findOrFail($model->user_id);
            $title = Titles::where('code', $model->title_code)->first(['id', 'name']);
            $unit = Unit::getTreeParentUnit($model->unit_code);
            $area = Area::getTreeParentArea($model->area_code);
            $page_title = $model->lastname .' '. $model->firstname;
            $position = Position::find($model->position_id);

            return view('user::backend.user.form', [
                'model' => $model,
                'page_title' => $page_title,
                'user' => $user,
                'title' => $title,
                'unit' => $unit,
                'area' => $area,
                'max_unit' => $max_unit,
                'level_name' => $level_name,
                'user_id'=>$model->user_id,
                'max_area' => $max_area,
                'level_name_area' => $level_name_area,
                'user_meta' => $user_meta,
                'certs' => $certs,
                'position' => $position,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }

        $model = new Profile();

        $page_title = trans('backend.add_new');

        return view('user::backend.user.form', [
            'model' => $model,
            'page_title' => $page_title,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'max_area' => $max_area,
            'level_name_area' => $level_name_area,
            'user_id'=>null,
            'certs' => $certs,
            'user_meta' => $user_meta,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'username' => 'required_if:id,==,|unique:user,username|min:6|max:32',
            'repassword' => 'same:password',
            'code' => 'required|unique:el_profile,code,'. $request->id,
            'lastname' => 'required',
            'firstname' => 'required',
            'email' => 'required_if:id,==,|email|unique:el_profile,email,'. $request->id,
            'gender' => 'required|in:1,0',
            'status' => 'required|in:0,1,2,3',
            'title_id' => 'required|exists:el_titles,id',
            'unit_id' => 'required|exists:el_unit,id',
        ],$request, Profile::getAttributeName());

        if ($request->auth == 'manual'){
            $this->validateRequest([
                'password' => 'nullable|min:8|max:32|required_if:id,==,',
            ], $request, Profile::getAttributeName());
        }
        if ($request->id && $request->id<=2){
            json_message('Không thể lưu user này', 'error');
        }


        if ($request->date_range) {
            if (!check_format_date($request->date_range)){
                json_message('Ngày cấp CMND phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->contract_signing_date) {
            if (!check_format_date($request->contract_signing_date)){
                json_message('Ngày kí Hợp đồng lao động phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->effective_date) {
            if (!check_format_date($request->effective_date)){
                json_message('Ngày hiệu lực / bổ nhiệm phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->expiration_date) {
            if (!check_format_date($request->expiration_date)){
                json_message('Ngày kết thúc / bổ nhiệm phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->dob) {
            if (!check_format_date($request->dob)){
                json_message('Ngày sinh phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->join_company) {
            if (!check_format_date($request->join_company)){
                json_message('Ngày vào làm phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->date_off) {
            if (!check_format_date($request->date_off)){
                json_message('Ngày nghỉ việc phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        $effective_date = date_convert($request->effective_date);
        $expiration_date = date_convert($request->expiration_date);
        if($request->expiration_date && $expiration_date < $effective_date){
            json_message('Ngày hết hạn phải sau ngày hiệu lực', 'error');
        }
        if($request->join_company && $request->join_company < $request->date_off){
            json_message('Ngày nghỉ việc phải sau ngày vào làm', 'error');
        }
        if ($request->date_title_appointment){
            if (!check_format_date($request->date_title_appointment)){
                json_message('Ngày bổ nhiệm chức danh phải định dạng kiểu Ngày/Tháng/Năm', 'error');
            }
        }
        if ($request->end_date_title_appointment && $request->date_title_appointment < $request->end_date_title_appointment){
            json_message('Ngày kết thúc bổ nhiệm chức danh phải sau ngày bổ nhiệm chức danh', 'error');
        }

        $unit = Unit::where('id', '=', $request->unit_id)->first();
        $title = Titles::where('id', '=', $request->title_id)->first();
        $area = Area::where('id', '=', @$unit->area_id)->first();

        $arr_user_meta = [
            'current_address' => $request->current_address,
            'current_address_map' => $request->current_address_map,
            'type_labor_contract' => $request->type_labor_contract,
            'name_contact_person' => $request->name_contact_person,
            'relationship' => $request->relationship,
            'phone_contact_person' => $request->phone_contact_person,
            'school' => $request->school,
            'majors' => $request->majors,
            'license' => $request->license,
            'suspension_date' => $request->suspension_date,
            'reason' => $request->reason,
            'commendation' => $request->commendation,
            'discipline' => $request->discipline,
            'marital_status' => $request->marital_status,
            'special_skills' => $request->special_skills,
            'note' => $request->note
        ];


            $user = User::firstOrNew(['id' => $request->id]);
            if ($request->status == 0) {
                $user->username = date('d/m/Y') . '-' . $request->username;
            } else {
                $user->username = $user->username ? $user->username: $request->username;
            }
            $user->auth = $request->auth;
            if ($request->auth == 'ldap') {
                $user->password = '';
            } else {
                $user->password = $request->password ? password_hash($request->password, PASSWORD_DEFAULT) : $user->password;
            }
            $user->email = $request->email;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->save();

            if ($user->id) {
                foreach ($arr_user_meta as $key => $value) {
                    $user_meta = UserMeta::query()->where('user_id', '=', $user->id)->where('key', '=', $key);

                    if ($user_meta->exists()) {
                        $user_meta->update([
                            'value' => $value,
                        ]);
                    } else {
                        $user_meta = new UserMeta();
                        $user_meta->user_id = $user->id;
                        $user_meta->key = $key;
                        $user_meta->value = $value;
                        $user_meta->save();
                    }
                }

                $model = Profile::firstOrNew(['id' => $user->id]);
                $model->fill($request->all());
                $model->id = $user->id;
                $model->user_id = $user->id;
                $model->area_code = $area ? $area->code : null;
                $model->unit_code = $unit->code;
                $model->unit_id = $unit->id;
                $model->title_code = $title->code;
                $model->expbank = $model->expbank ? $model->expbank : cal_date_by_month(now(), date_convert($model->join_company));
                if ($request->date_range)
                    $model->date_range = date_convert($request->date_range);
                if ($request->contract_signing_date)
                    $model->contract_signing_date = date_convert($request->contract_signing_date);
                if ($request->effective_date)
                    $model->effective_date = date_convert($request->effective_date);
                if ($request->expiration_date)
                    $model->expiration_date = date_convert($request->expiration_date);
                if ($request->dob)
                    $model->dob = date_convert($request->dob);
                if ($request->join_company)
                    $model->join_company = date_convert($request->join_company);
                if ($request->date_off)
                    $model->date_off = date_convert($request->date_off);
                if ($request->date_title_appointment)
                    $model->date_title_appointment = date_convert($request->date_title_appointment);
                    if ($request->end_date_title_appointment)
                        $model->end_date_title_appointment = date_convert($request->end_date_title_appointment);

                if (empty($request->id)) {
                    $model->id_code = Profile::generateShuffle();
                } elseif (!$model->id_code) {
                    $model->id_code = Profile::generateShuffle();
                }

//        $model->touch();
                if ($model->save()) {
                    json_result([
                        'status' => 'success',
                        'message' => trans('lageneral.successful_save'),
                        'redirect' => route('module.backend.user.edit', ['id' => $user->id])
                    ]);
                }
            }
            json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            if ($id<=2)
                continue;
            $profile = Profile::find($id);
            $user_manager = UnitManager::where('user_code', '=', $profile->code)->first();
            $user1 = OfflineRegister::where('user_id', $id)->first();
            $user2 = OnlineRegister::where('user_id', $id)->first();
            $user3 = TrainingTeacher::where('user_id', $id)->first();

            if ($user_manager || $user1 || $user2 || $user3){
                continue;
            }
            User::find($id)->delete();
            Profile::find($id)->delete();
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function getUnitByUser(Request $request, $id){
        $user = Profile::where('user_id', $id)->first();
        $unit = Unit::getTreeParentUnit($user->unit_code);

        $max_unit = Unit::getMaxUnitLevelWithValue();

        return view('user::backend.modal.unit_by_user', [
            'user' => $user,
            'unit' => $unit,
            'max_unit' => $max_unit,
        ]);
    }

    public function getAreaByUser(Request $request){
        $user = Profile::find($request->user_id);
        $area = Area::getTreeParentArea($user->area_code);

        $max_area = Area::getMaxAreaLevel();

        return view('user::backend.modal.area_by_user', [
            'user' => $user,
            'area' => $area,
            'max_area' => $max_area,
        ]);
    }

    public function importUser(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, [
            'import_file' => 'File import'
        ]);

        $file = $request->file('import_file');
        $name = 'import_user_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new UserImport(\Auth::user()))->queue($newfile)->chain([
                new NotifyUserOfCompletedImportUser(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('module.backend.user')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể tải lên file',
            'redirect' => route('module.backend.user')
        ]);
    }

    public function importWorkingProcess(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, [
            'import_file' => 'File import'
        ]);

        $file = $request->file('import_file');
        $name = 'import_working_process_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new WorkingProcessImport(\Auth::user()))->queue($newfile)->chain([
                new NotifyUserOfCompletedImportUser(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('module.backend.user')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể tải lên file',
            'redirect' => route('module.backend.user')
        ]);
    }

    public function importTrainingProgramLearned(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, [
            'import_file' => 'File import'
        ]);

        $file = $request->file('import_file');
        $name = 'import_training_program_learned_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new TrainingProgramLearnedImport(\Auth::user()))->queue($newfile)->chain([
                new NotifyUserOfCompletedImportUser(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('module.backend.user')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể tải lên file',
            'redirect' => route('module.backend.user')
        ]);
    }

    public function exportUser(Request $request)
    {
        $search = $request->export_search;
        $unit = $request->export_unit;
        $area = $request->export_area;
        $title = $request->export_title;
        $status = $request->export_status;
        return (new UserExport($search, $unit, $area, $title, $status))->download('danh_sach_nguoi_dung_'. date('d_m_Y') .'.xlsx');
    }

    public function showTrainingProcess(Request $request, $user_id)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('user::backend.trainingprocess.index',[
            'user_id'=>$user_id,
            'full_name'=>$this->getFullNameUser($user_id),
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getDataTrainingProcess(Request $request, $user_id) {

        $query = \DB::query();
        $query->select([
            'mark',
            'id',
            'course_id',
            'course_code',
            'course_name',
            'titles_name',
            'course_type',
            'process_type',
            'pass as result',
            'start_date',
            'end_date',
            'certificate',
        ]);
        $query->from('el_training_process');
        $query->where('user_id','=',$user_id);
        $count = $query->count();
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->image_cert = '';
            if ($row->certificate && $row->result == 1){
                $row->image_cert = route('module.backend.user.trainingprocess.certificate', ['course_id' => $row->course_id, 'course_type' => $row->course_type, 'user_id' => $user_id]);
            }

            if ($row->course_type==1){
                $course = OnlineCourse::find($row->course_id);
            }else{
                $course = OfflineCourse::find($row->course_id);
            }
            $row->training_form = '-';
            if($course) {
                $training_form = TrainingForm::where('id',$course->training_form_id)->first();
                $row->training_form = $training_form->name;
            }

            $row->course_type = $row->course_type==1?trans('backend.onlines'):trans('backend.offline');
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->score = $row->mark ? number_format($row->mark,2,',','.') : '';

            if ($row->process_type==2)
                $row->process_type = trans('backend.subject_complete');
            elseif ($row->process_type==4)
                $row->process_type = trans('backend.merge_subject');
            elseif ($row->process_type==5)
                $row->process_type = trans('backend.split_subject');
            else
                $row->process_type = '-';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function certificate($course_id, $course_type, $user_id){
        $query = \DB::query();
        $query->select([
            'b.end_date',
            'a.score',
            'b.cert_code',
            'c.created_at as date_complete',
        ]);
        $query->from('el_course_register_view AS a');
        $query->join('el_course_view AS b', function ($join){
            $join->on('a.course_id', '=', 'b.course_id');
            $join->on('a.course_type', '=', 'b.course_type');
        });
        $query->leftJoin('el_course_complete AS c', function ($join){
            $join->on('c.course_id', '=', 'b.course_id');
            $join->on('c.course_type', '=', 'b.course_type');
        });
        $query->where('a.user_id','=', $user_id);
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.course_type', '=', $course_type);

        $model = $query->first();

        $profile = Profile::find($user_id);
        $unit = @$profile->unit->name;
        $title = @$profile->titles->name;
        $fullname = $profile->full_name;
        //$fullname = mb_convert_case($fullname, MB_CASE_UPPER, "UTF-8");

        $day = get_date(@$model->date_complete, 'd');
        $month = get_date(@$model->date_complete, 'm');
        $year = get_date(@$model->date_complete, 'Y');

        if ($course_type == 1){
            $course = OnlineCourse::find($course_id);
        }else{
            $course = OfflineCourse::find($course_id);
        }
        $certificate = \Modules\Certificate\Entities\Certificate::find($course->cert_code);

        $course_name = $course->name;

        $storage = \Storage::disk('upload');
        $path = $storage->path($certificate->image);
        $temp = str_replace($certificate->image, str_replace('.', '_'.$course_id.'.', $certificate->image), $path);

        $image = ImageManagerStatic::make($path);

        $image->text($fullname, 500, 520, function ($font){
            $font->file(public_path('fonts/UTM Wedding K&T.ttf'));
            $font->size(100);
            $font->color('#bd8e34');
        });

        /*$image->text($unit, 710, 1630, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(50);
        });*/

        $image->text($title, 870, 655, function ($font){
            $font->file(public_path('fonts/FiraSansExtraCondensed-Regular.ttf'));
            $font->size(50);
            $font->align('center');
        });

        $center_x    = 870;
        $center_y    = 830;
        $max_len     = 100;
        $font_height = 20;

        $lines = explode("/n", wordwrap($course_name, $max_len,"/n", true));
        $y     = $center_y - ((count($lines) - 1) * $font_height);
        foreach ($lines as $line) {
            $line = Str::upper($line);

            $image->text($line, $center_x, $y, function ($font) {
                $font->file(public_path('fonts/FiraSansExtraCondensed-Bold.ttf'));
                $font->size(60);
                $font->align('center');
                $font->color('#E53336');
            });
            $y += $font_height * 2;
        }

        /*$image->text($day, 1535, 2325, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(40);
        });

        $image->text($month, 1725, 2325, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(40);
        });

        $image->text($year, 1895, 2325, function ($font){
            $font->file(public_path('fonts/timesbd.ttf'));
            $font->size(40);
        });*/

        $image->save($temp);

        $headers = array(
            'Content-Type: application/pdf',
        );

        return response()->download($temp, 'chung_chi_'.Str::slug($fullname, '_').'.png', $headers);

        //return \Storage::download($temp);
    }

    public function showQuizResult(Request $request, $user_id)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('user::backend.quizresult.index',[
            'user_id'=>$user_id,
            'full_name'=>$this->getFullNameUser($user_id),
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getDataQuizResult(Request $request, $user_id) {
        $query = \DB::query()
            ->select([
                'a.quiz_id',
                'c.id',
                'c.code',
                'c.name',
                'c.limit_time',
                'b.start_date',
                'b.end_date',
                'd.grade',
                'd.result',
                'd.reexamine'
            ])
            ->from('el_quiz_register as a')
            ->join('el_quiz_part as b','b.id','=','a.part_id')
            ->join('el_quiz as c','c.id','=','b.quiz_id')
            ->leftJoin('el_quiz_result as d',function ($join){
                $join->on('a.user_id','=','d.user_id');
                $join->on('d.quiz_id','=','a.quiz_id');
            })
            ->where('a.user_id','=',$user_id);
        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date,'d/m/Y H:i');
            $row->end_date = get_date($row->end_date,'d/m/Y H:i');
            $row->grade = number_format(($row->reexamine ? $row->reexamine : $row->grade),2,',','.');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function showRoadmap(Request $request, $user_id)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('user::backend.roadmap.index',[
            'user_id'=>$user_id,
            'full_name'=>$this->getFullNameUser($user_id),
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getDataRoadmap(Request $request, $user_id)
    {

        $user = \DB::table('el_profile_view')->where(['user_id'=>$user_id])->first();
//        $subQuery = \DB::table('el_training_process')
//            ->where('titles_code','=', $user->title_code)
//            ->groupBy('subject_id')
//            ->select([
//                \DB::raw('MAX(id) as id'),
//                'subject_id',
//            ]);

        $query = \DB::query();
        $query->select([
            /*'c.id',
            'c.subject_code',
            'c.subject_name',
            'c.process_type',
            'c.start_date',
            'c.end_date',
            'c.time_complete',
            'c.mark',
            'c.pass',
            'c.note',*/
            'a.subject_id',
            'a.completion_time',
            'd.code as training_program_code',
            'd.name as training_program_name',
            'subject.id',
            'subject.code as subject_code',
            'subject.name as subject_name',
        ]);
        $query->from("el_trainingroadmap AS a");
        /*$query->joinSub($subQuery,'b', function ($join){
            $join->on('b.subject_id', '=', 'a.subject_id');
        });
        $query->leftJoin('el_training_process as c', function ($join){
            $join->on('c.id', '=', 'b.id');
        });*/
        $query->leftJoin('el_subject as subject', 'subject.id', '=', 'a.subject_id');
        $query->leftJoin('el_training_program as d','d.id','=','a.training_program_id');
        $query->where('a.title_id','=', $user->title_id);
        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            $training_process = TrainingProcess::whereSubjectId($row->subject_id)->where(['titles_code'=> $user->title_code,'user_id'=>$user_id,'pass'=>1])->first();

            $row->start_date = $training_process ? get_date($training_process->start_date) : '';
            $row->end_date = $training_process ? get_date($training_process->end_date) : '';
            if ($training_process )
                $row->score = ($training_process && $training_process->mark) ? number_format($training_process->mark,2,',','.') : '';
            else
                $row->score = '';
            if ($row->training_program_code){
                $row->result = ($training_process && $training_process->pass==1)? trans('backend.finish') :'';
            }
            $row->start_effect = $row->completion_time && $training_process && $training_process->time_complete ? get_date($training_process->time_complete) :'-';
            $row->end_effect = $row->completion_time && $training_process && $training_process->time_complete ? get_date(strtotime($training_process->time_complete.' '.$row->completion_time.' days')) :'-';
            $row->status = $row->result;
            $row->note = $training_process ? $training_process->note : '';
            if ($training_process){
                if ($training_process->process_type==2)
                    $row->process_type = trans('backend.subject_complete');
                elseif ($training_process->process_type==4)
                    $row->process_type = trans('backend.merge_subject');
                elseif ($training_process->process_type==5)
                    $row->process_type = trans('backend.split_subject');
                else
                    $row->process_type = '-';
            }
            else
                $row->process_type = '-';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function infoChange(){
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('user::backend.user.approve_info',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getDataHistoryChange(Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = HistoryChangeInfo::query();
        $query->select([
            'a.*',
            'b.code as user_code',
            'b.firstname',
            'b.lastname',
            'c.name as unit_name',
            'd.name as unit_manager',
        ]);
        $query->from('el_history_change_info AS a');
        $query->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_unit AS c', 'c.code', '=', 'b.unit_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('key','!=','avatar');

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->full_name = $row->lastname . ' ' . $row->firstname;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function approveUserInfo(Request $request)
    {
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);

        foreach ($ids as $id) {
            $history = HistoryChangeInfo::find($id);
            $history->status = $status;
            $history->approve_by = \Auth::id();
            $history->approve_time = date('Y-m-d H:i:s');
            $history->save();

            if ($status == 1){
                if ($history->key == 'avatar'){
                    $model =Profile::where('user_id', '=', $history->user_id)->first();
                    $model->avatar = $history->value_new;
                    $model->save();
                } elseif($history->key == 'phone') {
                    $model = Profile::where('user_id', '=', $history->user_id)->first();
                    $model->phone = $history->value_new;
                    $model->save();
                } elseif($history->key == 'email') {
                    $model = Profile::where('user_id', '=', $history->user_id)->first();
                    $model->email = $history->value_new;
                    $model->save();
                } else {
                    $user_meta = UserMeta::where('user_id', $history->user_id)->where('key', $history->key)->first();
                    if ($user_meta){
                        UserMeta::where('user_id', $history->user_id)->where('key', $history->key)
                            ->update([
                                'value' => $history->value_new,
                            ]);
                    }else{
                       UserMeta::insert([
                            'user_id' => $history->user_id,
                            'key' => $history->key,
                            'value' => $history->value_new,
                       ]);
                    }
                }
                json_result([
                    'status' => 'success',
                    'message' => 'Duyệt thành công',
                ]);
            } else {
                json_result([
                    'status' => 'error',
                    'message' => 'Duyệt không thành công',
                ]);
            }
        }
        
    }

    private function getFullNameUser($user_id)
    {
        $query = Profile::where('user_id', '=', $user_id);
        if ($query->exists()) {
            $data = $query->first(['firstname', 'lastname']);
            return $data->lastname . ' '. $data->firstname;
        }

        return '';
    }

    public function showTrainingByTitle(Request $request, $user_id)
    {
        return view('user::backend.training_by_title.index',[
            'user_id' => $user_id,
            'full_name'=>$this->getFullNameUser($user_id)
        ]);
    }

    public function getDataTrainingByTitle(Request $request, $user_id)
    {
        $user = ProfileView::where('user_id', '=', $user_id)->first();

        $query = TrainingByTitleDetail::query();
        $query->select([
            'a.subject_code',
            'a.subject_name',
            'b.course_id',
            'b.code as course_code',
            'b.name as course_name',
            'b.course_type',
            'b.start_date',
            'b.end_date',
        ]);
        $query->from("el_training_by_title_detail AS a");
        $query->leftJoin('el_course_view as b', 'b.subject_id', '=', 'a.subject_id');
        $query->where('b.status','=', 1);
        $query->where('a.title_id','=', $user->title_id);

        $count = $query->count();
        $rows = $query->get();
        foreach ($rows as $row) {
            if ($row->course_type == 1){
                $result = OnlineResult::whereCourseId($row->course_id)->where('user_id', '=', $user_id)->first();
                $course_type = 'Trực tuyến';
            }else{
                $result = OfflineResult::whereCourseId($row->course_id)->where('user_id', '=', $user_id)->first();
                $course_type = 'Tập trung';
            }
            $row->score = $result ? $result->score : '';
            $row->result = $result ? ($result->result == 1 ? 'Hoàn thành' : 'Chưa hoàn thành') : '';

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->course_type = $course_type;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function syncAPIUser(Request $request)
    {
         \Modules\User\Entities\User::syncAPIUser($request->id);
    }
}
