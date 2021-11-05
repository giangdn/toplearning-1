<?php

namespace Modules\Offline\Http\Controllers;

use App\Automail;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\Permission;
use App\Models\Categories\UnitManager;
use App\Models\Categories\Area;
use App\PermissionTypeUnit;
use App\UserPermissionType;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineCourse;
use App\Profile;
use App\ProfileView;
use Modules\Offline\Entities\OfflineRegisterApprove;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Exports\RegisterExport;
use Modules\Offline\Imports\RegisterImport;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Offline\Entities\OfflineObject;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use Modules\User\Entities\TrainingProcess;

class RegisterController extends Controller
{
    public function index($course_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $errors = session()->get('errors');
        \Session::forget('errors');

        $offline = OfflineCourse::findOrFail($course_id);

        $quiz_part = function ($quiz_id) {
            return QuizPart::where('quiz_id', '=', $quiz_id)->get();
        };
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $user_has_role_register = UserRole::query()
            ->whereIn('role_id', function ($sub){
                $sub->select(['a.role_id'])
                    ->from('el_role_has_permissions as a')
                    ->leftJoin('el_permissions as b', 'b.id', '=', 'a.permission_id')
                    ->whereIn('b.name', ['offline-course-register', 'offline-course-register-create'])
                    ->pluck('a.role_id')
                    ->toArray();
            })
            ->where('user_id', '!=', Auth::id())
            ->where('user_id', '>', 2)
            ->get();

        $user_invited = false;
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = true;
        }

        return view('offline::backend.register.index', [
            'offline' => $offline,
            'course_id' => $course_id,
            'quiz_part' => $quiz_part,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'user_has_role_register' => $user_has_role_register,
            'user_invited' => $user_invited,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function getData($course_id, Request $request) {
        $search = $request->input('search');
        $join_company = $request->input('join_company');
        $title = $request->input('title');
        $unit = $request->unit;
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_invited = false;
        $condition = '';
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = true;
            $user_permission_type = UserPermissionType::query()
                ->select(['permission_type_id'])
                ->whereUserId(Auth::id())
                ->groupBy('permission_type_id')
                ->first();
            $condition=PermissionTypeUnit::conditionUnitGroup(@$user_permission_type->permission_type_id);
        }

        $manager = UnitManager::getIdUnitManagedByUser();

//        $query = OfflineRegister::query();
//        $query->select([
//            'a.*',
//            'b.lastname',
//            'b.firstname',
//            'b.email',
//            'b.expbank',
//            'b.code',
//            'c.name AS title_name',
//            'd.name AS unit_name',
//            'e.name AS parent_name'
//        ]);
//        $query->from('el_offline_register AS a');
//        $query->join('el_profile AS b', 'b.user_id', '=', 'a.user_id');
//        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
//        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
//        $query->leftJoin('el_unit AS e', 'e.code', '=', 'd.parent_code');
//        $query->where('a.course_id', '=', $course_id);
        $query = OfflineRegisterView::query();
        $query->select(['a.*','u.username']);
        $query->from('el_offline_register_view as a');
        $query->leftJoin('el_unit AS b', 'b.id', '=', 'a.unit_id');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
        $query->leftJoin('user as u','u.id','=','a.user_id');
        $query->where('a.course_id', '=', $course_id);

        if ($user_invited){
            $query->whereExists(function ($queryExists) use ($condition){
                $queryExists->select('id')
                    ->from('el_unit_view')
                    ->whereColumn(['id'=>'unit_id']);
                if ($condition)
                    $queryExists->whereRaw($condition);
                else
                    $queryExists->whereRaw("1=-1");
            });
        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                // $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('a.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('u.username', 'like', '%'. $search .'%');
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
        if ($title) {
            $query->where('a.title_id', '=', $title);
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('a.unit_id', $unit_id);
                $sub_query->orWhere('a.unit_id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
//        foreach ($rows as $row){
//        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataNotRegister($course_id, Request $request){
        $search = $request->input('search');
        $join_company = $request->input('join_company');
        $title = $request->input('title');
        $unit = $request->unit;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $manager = UnitManager::getIdUnitManagedByUser();

        $user_invited = false;
        $condition = '';
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = true;
            $user_permission_type = UserPermissionType::query()
                ->select(['permission_type_id'])
                ->whereUserId(Auth::id())
                ->groupBy('permission_type_id')
                ->first();
            $condition=PermissionTypeUnit::conditionUnitGroup(@$user_permission_type->permission_type_id);
        }

        $query = ProfileView::query();
        $query->select([
            'a.*',
            'user.username',
        ]);
        $query->from('el_profile_view AS a');
        $query->leftJoin('user AS user', 'user.id', '=', 'a.user_id');
        $query->where('a.user_id', '>', 2);
        $query->where('a.type_user', '=', 1);

        if (OfflineObject::where('course_id', $course_id)->exists()) {
            $query->where(function ($sub) use ($course_id){
                $sub->orWhere(function($sub_query) use ($course_id) {
                    $sub_query->whereIn('a.title_id', function ($sub_query2) use ($course_id){
                        $sub_query2->select(['title_id']);
                        $sub_query2->from('el_offline_object');
                        $sub_query2->where('course_id', '=', $course_id);
                    });
                    $sub_query->whereIn('a.unit_id', function ($sub_query3) use ($course_id){
                        $sub_query3->select(['unit_id']);
                        $sub_query3->from('el_offline_object');
                        $sub_query3->where('course_id', '=', $course_id);
                    });
                });
                $sub->orWhereIn('a.unit_id',function ($sub_query) use ($course_id){
                    $sub_query->select(['unit_id']);
                    $sub_query->from('el_offline_object');
                    $sub_query->whereNull('title_id');
                    $sub_query->where('course_id', '=', $course_id);
                });
            });
        }
        $query->whereNotIn('a.user_id', function($sub_query) use ($course_id) {
            $sub_query->select(['user_id']);
            $sub_query->from('el_offline_register');
            $sub_query->where('course_id', '=', $course_id);
        });

        if ($user_invited){
            $query->whereExists(function ($queryExists) use ($condition){
                $queryExists->select('id')
                    ->from('el_unit_view')
                    ->whereColumn(['id'=>'c.id']);
                if ($condition)
                    $queryExists->whereRaw($condition);
                else
                    $queryExists->whereRaw("1=-1");
            });
        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('a.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('user.username', 'like', '%'. $search .'%');
            });
        }
        if ($join_company){
            $query->where('a.expbank', '=', $join_company);
        }
        if ($title) {
            $query->where('a.title_id', '=', $title);
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('a.unit_id', $unit_id);
                $sub_query->orWhere('a.unit_id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){

            $row->join_company = get_date($row->join_company, 'd/m/Y');

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

    public function form($course_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $offline = OfflineCourse::findOrFail($course_id);
        return view('offline::backend.register.form', [
            'course_id' => $course_id,
            'offline' => $offline,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function save($course_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, OfflineRegister::getAttributeName());

        $user_invited = false;
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = true;
            $num_register = $check_user_invited->first()->num_register;
        }

        $ids = $request->input('ids', null);
        $course = OfflineCourse::findOrFail($course_id);
        $subject = Subject::findOrFail($course->subject_id);
        foreach($ids as $id){
            if ($user_invited){
                if ($num_register == 0){
                    continue;
                }else{
                    $num_register -= 1;

                    OfflineInviteRegister::query()
                        ->where('course_id', '=', $course_id)
                        ->where('user_id', '=', Auth::id())
                        ->update([
                            'num_register' => $num_register
                        ]);
                }
            }


            if (OfflineRegister::checkExists($id, $course_id)) {
                continue;
            }
            $model = new OfflineRegister();
            $model->user_id = $id;
            $model->course_id = $course_id;
            if ($model->save()) {

                // update training process
                $profile = \DB::table('el_profile_view')->where('user_id','=',$id)->first();
                TrainingProcess::updateOrCreate(
                    [
                        'user_id'=>$id,
                        'course_id'=>$course_id,
                        'course_type'=>2
                    ],
                    [
                        'course_code'=>$course->code,
                        'course_name'=>$course->name,
                        'subject_id'=>$subject->id,
                        'subject_code'=>$subject->code,
                        'subject_name'=>$subject->name,
                        'titles_code'=>$profile->title_code,
                        'titles_name'=>$profile->title_name,
                        'unit_code'=>$profile->unit_code,
                        'unit_name'=>$profile->unit_name,
                        'start_date'=>$course->start_date,
                        'end_date'=>$course->end_date,
                        'process_type'=>1,
                        'certificate'=>$course->cert_code,
                    ]
                );
                ///
                $users = UnitManager::getManagerOfUser($model->user_id);
                foreach ($users as $user_id){
                    $signature = getMailSignature($user_id);

                    $automail = new Automail();
                    $automail->template_code = 'approve_register_unit';
                    $automail->params = [
                        'signature' => $signature,
                        'code' => $course->code,
                        'name' => $course->name,
                        'start_date' => $course->start_date,
                        'end_date' => $course->end_date,
                        'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => 2])
                    ];

                    $automail->users = [$user_id];
                    $automail->object_id = $course->id;
                    $automail->object_type = 'approve_offline_register_unit';
                    $automail->addToAutomail();
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $offline_register = OfflineRegister::find($id);
            $result = OfflineResult::where('register_id', '=', $id);
            if ($result->exists() ){
                continue;
            }
            $quizs = \DB::query()
                ->select(['a.id', 'b.user_id'])
                ->from('el_quiz as a')
                ->leftJoin('el_quiz_register as b', 'b.quiz_id', '=', 'a.id')
                ->where('a.course_id', '=', $offline_register->course_id)
                ->where('b.user_id', '=', $offline_register->user_id)
                ->get();
            if (count($quizs) > 0){
                $count = 0;
                foreach ($quizs as $quiz){
                    $result = QuizResult::where('quiz_id', '=', $quiz->id)
                        ->where('user_id', '=', $quiz->user_id)
                        ->where('type', '=', 1)
                        ->first();
                    if ($result){
                        $count++;
                        continue;
                    }else{
                        QuizRegister::where('quiz_id', '=', $quiz->id)->where('user_id', '=', $quiz->user_id)->delete();
                    }
                }
                if ($count == 0){
                    $user_invited = OfflineInviteRegister::query()
                        ->where('course_id', '=', $offline_register->course_id)
                        ->where('user_id', '=', $offline_register->created_by)
                        ->first();
                    if ($user_invited){
                        OfflineInviteRegister::query()
                            ->where('course_id', '=', $offline_register->course_id)
                            ->where('user_id', '=', $offline_register->created_by)
                            ->update([
                                'num_register' => $user_invited->num_register + 1
                            ]);
                    }

                    $offline_course = OfflineCourse::find($offline_register->course_id);
                    TrainingProcess::where(['user_id'=>$offline_register->user_id,'course_id'=>$offline_course->id,'course_type'=>2])->delete();
                    $offline_register->delete();
                }
            }else{
                $user_invited = OfflineInviteRegister::query()
                    ->where('course_id', '=', $offline_register->course_id)
                    ->where('user_id', '=', $offline_register->created_by)
                    ->first();
                if ($user_invited){
                    OfflineInviteRegister::query()
                        ->where('course_id', '=', $offline_register->course_id)
                        ->where('user_id', '=', $offline_register->created_by)
                        ->update([
                            'num_register' => $user_invited->num_register + 1
                        ]);
                }

                $offline_course = OfflineCourse::find($offline_register->course_id);
                TrainingProcess::where(['user_id'=>$offline_register->user_id,'course_id'=>$offline_course->id,'course_type'=>2])->delete();
                $offline_register->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importRegister($course_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit');

        $import = new RegisterImport($course_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = $unit > 0 ?  route('module.training_unit.offline.register', ['id' => $course_id]) : route('module.offline.register', ['id' => $course_id]);

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => $redirect,
        ]);
    }

    public function addToQuiz($course_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Học viên',
        ]);

        $offline = OfflineCourse::find($course_id);

        $part_id = $request->part_id;
        $ids = $request->ids;
        $errors = [];

        foreach ($ids as $id){
            $register = OfflineRegister::find($id);
            $full_name = Profile::fullname($register->user_id);

            if ($register->status != 1){
                $errors[] = "Nhân viên <b>$full_name</b> chưa được duyệt";
                continue;
            }

            QuizRegister::query()
                ->updateOrCreate([
                    'quiz_id' => $offline->quiz_id,
                    'user_id' => $register->user_id,
                    'type' => 1,
                ],[
                    'part_id' => $part_id,
                ]);
            /*if ($query->exists()) {
                $query->update([
                    'part_id' => $part_id
                ]);
            }
            else {
                $query->insert([
                    'quiz_id' => $offline->quiz_id,
                    'user_id' => $register->user_id,
                    'part_id' => $part_id,
                    'type' => 1,
                ]);
            }*/
        }

        session()->put('errors', $errors);
        session()->save();

        json_message(trans('lageneral.successful_save'));
    }

    public function exportRegister($course_id){
        return (new RegisterExport($course_id))->download('danh_sach_ghi_danh_khoa_hoc_'. date('d_m_Y') .'.xlsx');
    }

    public function inviteUserRegister($course_id, Request $request) {
        $this->validateRequest([
            'user_id' => 'required',
            'num_register' => 'required',
        ], $request, [
            'user_id' => 'Người có vai trò',
            'num_register' => 'Số lượng được ghi danh',
        ]);

        $user_id = $request->user_id;
        $role_id = $request->role_id;
        $num_register = $request->num_register;
        $offline = OfflineCourse::whereId($course_id)->first();

        $model = OfflineInviteRegister::firstOrNew(['course_id' => $course_id, 'user_id' => $user_id]);
        $model->user_id = $user_id;
        $model->role_id = $role_id;
        $model->course_id = $course_id;
        $model->unit_by = $offline->unit_by;
        $model->num_register = $num_register;
        $model->save();

        json_message(trans('lageneral.successful_save'));
    }

    public function getDataInviteUserRegister($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineInviteRegister::query()
            ->where('course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->user_code = Profile::usercode($row->user_id);
            $row->user_name = Profile::fullname($row->user_id);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeInviteUserRegister($course_id, Request $request) {
        $ids = $request->input('ids', null);
        OfflineInviteRegister::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function sendMailUserRegisted($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request,[
            'ids' => 'Học viên',
        ]);

        $course = OfflineCourse::find($course_id);
        $ids = $request->input('ids', null);
        $users = OfflineRegister::whereIn('id', $ids)->get();

        foreach ($users as $user) {
            $signature = getMailSignature($user->user_id);

            $automail = new Automail();
            $automail->template_code = 'registered_course';
            $automail->params = [
                'signature' => $signature,
                'gender' => $user->user->gender=='1'?'Anh':'Chị',
                'full_name' => $user->user->full_name,
                'course_code' => $course->code,
                'course_name' => $course->name,
                'course_type' => 'Tập trung',
                'start_date' => get_date($course->start_date),
                'end_date' => get_date($course->end_date),
                'training_location' => $course->training_location?$course->training_location->name:'',
                'url' => route('module.offline.detail', ['id' => $course->id])
            ];
            $automail->users = [$user->user_id];
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course->id;
            $automail->object_type = 'register_approved_offline';
            $automail->addToAutomail();
        }

        json_message('Gửi mail thành công','success');
    }
}
