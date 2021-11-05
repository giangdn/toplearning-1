<?php

namespace Modules\Online\Http\Controllers;

use App\Automail;
use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Permission;
use App\Models\Categories\UnitManager;
use App\PermissionTypeUnit;
use App\Scopes\DraftScope;
use App\UnitView;
use App\UserPermissionType;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineCourse;
use App\Profile;
use App\ProfileView;
use Modules\Online\Entities\OnlineRegisterApprove;
use Modules\Online\Entities\OnlineResult;
use Modules\Online\Imports\RegisterImport;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\User\Entities\TrainingProcess;

class RegisterController extends Controller
{
    public function index($course_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $errors = session()->get('errors');
        \Session::forget('errors');

        $online = OnlineCourse::findOrFail($course_id);

        $quiz_exists = OnlineCourseActivity::where('course_id', '=', $course_id)
            ->where('activity_id', '=', 2)
            ->get();
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $user_has_role_register = UserRole::query()
            ->whereIn('role_id', function ($sub){
                $sub->select(['a.role_id'])
                    ->from('el_role_has_permissions as a')
                    ->leftJoin('el_permissions as b', 'b.id', '=', 'a.permission_id')
                    ->whereIn('b.name', ['online-course-register', 'online-course-register-create'])
                    ->pluck('a.role_id')
                    ->toArray();
            })
            ->where('user_id', '!=', Auth::id())
            ->where('user_id', '>', 2)
            ->get();

        $user_invited = false;
        $check_user_invited = OnlineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = true;
        }

        return view('online::backend.register.index', [
            'online' => $online,
            'quiz_exists' => $quiz_exists,
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
        $title = $request->input('title');
        $unit = $request->unit;
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $manager = UnitManager::getIdUnitManagedByUser();

        $user_invited = false;
        $condition = '';
        $check_user_invited = OnlineInviteRegister::query()
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

        $query = OnlineRegister::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.email',
            'b.code',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.status AS unit_approve',
            'f.name AS parent_name',
            'a.approved_step',
            'g.name as unit_manager',
            'u.username',
        ]);

        $query->from('el_online_register AS a');
        $query->join('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'd.area_id');
        $query->leftJoin('el_unit AS g', 'g.code', '=', 'd.parent_code');
        $query->leftJoin('el_online_register_approve AS e', 'e.register_id', '=', 'a.id');
        $query->leftJoin('el_unit AS f', 'f.code', '=', 'd.parent_code');
        $query->leftJoin('user AS u', 'u.id', '=', 'b.user_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.user_type', '=', 1);

        if ($user_invited){
            $query->whereExists(function ($queryExists) use ($condition){
                $queryExists->select('id')
                    ->from('el_unit_view')
                    ->whereColumn(['id'=>'d.id']);
                if ($condition)
                    $queryExists->whereRaw($condition);
                else
                    $queryExists->whereRaw("1=-1");
            });
        }

        /*if (!Permission::isAdmin()){
            $query->whereIn('d.id', $manager);
        }*/

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                // $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('b.lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.email', 'like', '%'. $search .'%');
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
            $query->where('c.id', '=', $title);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('d.id', $unit_id);
                $sub_query->orWhere('d.id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $quiz_register = QuizRegister::where('user_id', '=', $row->user_id)->where('type', '=', 1)->get();

            $quiz_name = [];
            foreach ($quiz_register as $register){
                $quiz = Quiz::query()
                    ->select(['name'])
                    ->from('el_quiz')
                    ->where('id', '=', $register->quiz_id)
                    ->where('course_id','=', $row->course_id)
                    ->where('course_type', '=', 1)
                    ->get();

                foreach ($quiz as $item){
                    $quiz_name[] = $item->name;
                }
            }

            $row->quiz_name = implode(', ', $quiz_name);

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

    public function getDataNotRegister($course_id, Request $request){
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->unit;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $course = OnlineCourse::where('id', '=', $course_id)->first();

        $manager = UnitManager::getIdUnitManagedByUser();

        $user_invited = false;
        $condition = '';
        $check_user_invited = OnlineInviteRegister::query()
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

        if (OnlineObject::where('course_id', $course_id)->exists()) {
            $query->where(function ($sub) use ($course_id){
                $sub->orWhere(function($sub_query) use ($course_id) {
                    $sub_query->whereIn('a.title_id', function ($sub_query2) use ($course_id){
                        $sub_query2->select(['title_id']);
                        $sub_query2->from('el_online_object');
                        $sub_query2->where('course_id', '=', $course_id);
                    });
                    $sub_query->whereIn('a.unit_id', function ($sub_query3) use ($course_id){
                        $sub_query3->select(['unit_id']);
                        $sub_query3->from('el_online_object');
                        $sub_query3->where('course_id', '=', $course_id);
                    });
                });
                $sub->orWhereIn('a.unit_id',function ($sub_query) use ($course_id){
                    $sub_query->select(['unit_id']);
                    $sub_query->from('el_online_object');
                    $sub_query->whereNull('title_id');
                    $sub_query->where('course_id', '=', $course_id);
                });
            });
        }
        $query->whereNotIn('a.user_id', function($sub_query) use ($course_id) {
            $sub_query->select(['user_id']);
            $sub_query->from('el_online_register');
            $sub_query->where('course_id', '=', $course_id);
            $sub_query->where('user_type', '=', 1);
        });

        if ($user_invited){
            $query->whereExists(function ($queryExists) use ($condition){
                $queryExists->select('id')
                    ->from('el_unit_view')
                    ->whereColumn(['id'=>'a.unit_id']);
                if ($condition)
                    $queryExists->whereRaw($condition);
                else
                    $queryExists->whereRaw("1=-1");
            });
        }
        /*if (!Permission::isAdmin()){
            $query->whereIn('c.id', $manager);
        }*/

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('a.full_name', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('user.username', 'like', '%'. $search .'%');
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

        /*if ($course->unit_id) {
            $query->where('c.id', '=', $course->unit_id);
        }*/

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){

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
        $online = OnlineCourse::findOrFail($course_id);
        return view('online::backend.register.form', [
            'online' => $online,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function save($course_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, OnlineRegister::getAttributeName());

        $user_invited = false;
        $check_user_invited = OnlineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = true;
            $num_register = $check_user_invited->first()->num_register;
        }

        $course = OnlineCourse::findOrFail($course_id);
        $ids = $request->input('ids', null);
        $subject = Subject::findOrFail($course->subject_id);

        foreach($ids as $id){
            if ($user_invited){
                if ($num_register == 0){
                    continue;
                }else{
                    $num_register -= 1;

                    OnlineInviteRegister::query()
                        ->where('course_id', '=', $course_id)
                        ->where('user_id', '=', Auth::id())
                        ->update([
                            'num_register' => $num_register
                        ]);
                }
            }

            if (OnlineRegister::checkExists($id, $course_id)) {
                continue;
            }
            // update training process
            $profile = \DB::table('el_profile_view')->where('user_id','=',$id)->first();
            TrainingProcess::updateOrCreate(
                [
                    'user_id'=>$id,
                    'user_type'=>1,
                    'course_id'=>$course_id,
                    'course_type'=>1
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
            $model = new OnlineRegister();
            $model->user_id = $id;
            $model->course_id = $course_id;
            if ($course->auto == 1){
                $model->status = 1;

                $quizs = Quiz::where('course_id', '=', $course_id)
                    ->where('status', '=', 1)->get();
                if ($quizs){
                    foreach ($quizs as $quiz){
                        $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
                        if ($quiz_part){
                            QuizRegister::query()
                                ->updateOrCreate([
                                    'quiz_id' => $quiz->id,
                                    'user_id' => $id,
                                    'type' => 1,
                                ],[
                                    'part_id' => $quiz_part->id,
                                ]);
                        }else{
                            continue;
                        }
                    }
                }
                $model->save();
            }else{
                if ($model->save()) {
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
                            'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => 1])
                        ];

                        $automail->users = [$user_id];
                        $automail->object_id = $course->id;
                        $automail->object_type = 'approve_online_register_unit';
                        $automail->addToAutomail();
                    }
                }
            }
        }

        json_message(trans('lageneral.successful_save'));
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        foreach ($ids as $id){
            $online_register = OnlineRegister::find($id);
            $result = OnlineResult::where('register_id', '=', $id);
            if ($result->exists()){
                continue;
            }

            $quizs = \DB::query()
                ->select(['a.id', 'b.user_id'])
                ->from('el_quiz as a')
                ->leftJoin('el_quiz_register as b', 'b.quiz_id', '=', 'a.id')
                ->where('a.course_id', '=', $online_register->course_id)
                ->where('b.user_id', '=', $online_register->user_id)
                ->where('b.type', '=', 1)
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
                        QuizRegister::where('quiz_id', '=', $quiz->id)
                            ->where('user_id', '=', $quiz->user_id)
                            ->where('type', '=', 1)
                            ->delete();
                    }
                }
                if ($count == 0){
                    $user_invited = OnlineInviteRegister::query()
                        ->where('course_id', '=', $online_register->course_id)
                        ->where('user_id', '=', $online_register->created_by)
                        ->first();
                    if ($user_invited){
                        OnlineInviteRegister::query()
                            ->where('course_id', '=', $online_register->course_id)
                            ->where('user_id', '=', $online_register->created_by)
                            ->update([
                                'num_register' => $user_invited->num_register + 1
                            ]);
                    }

                    $online_register->delete();
                }
            }else{
                $user_invited = OnlineInviteRegister::query()
                    ->where('course_id', '=', $online_register->course_id)
                    ->where('user_id', '=', $online_register->created_by)
                    ->first();
                if ($user_invited){
                    OnlineInviteRegister::query()
                        ->where('course_id', '=', $online_register->course_id)
                        ->where('user_id', '=', $online_register->created_by)
                        ->update([
                            'num_register' => $user_invited->num_register + 1
                        ]);
                }

                $online_register->delete();
            }
            $online_course = OnlineCourse::find($online_register->course_id);
            TrainingProcess::where(['user_id'=>$online_register->user_id,'course_id'=>$online_course->id,'course_type'=>2,'user_type' => 2])->delete();
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

        $redirect = route('module.online.register', ['id' => $course_id]);

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

        $quiz_id = $request->input('quiz_id');
        $part_id = $request->part_id;
        $ids = $request->ids;
        $errors = [];

        foreach ($ids as $id){
            $register = OnlineRegister::find($id);
            $full_name = Profile::fullname($register->user_id);

            if ($register->status != 1){
                $errors[] = "Nhân viên <b>$full_name</b> chưa được duyệt";
                continue;
            }

            $result = QuizResult::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $register->user_id)
                ->where('type', '=', 1)
                ->first();

            if ($result){
                $errors[] = "Nhân viên <b>$full_name</b> đã thi. Không thể sửa";
                continue;
            }

            QuizRegister::query()
            ->updateOrCreate([
                'quiz_id' => $quiz_id,
                'user_id' => $register->user_id,
                'type' => 1,
            ],[
                'part_id' => $part_id,
            ]);
        }

        session()->put('errors', $errors);
        session()->save();

        json_message(trans('lageneral.successful_save'));
    }

    public function getDataInviteUserRegister($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineInviteRegister::query()
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
        OnlineInviteRegister::destroy($ids);

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

        $course = OnlineCourse::find($course_id);
        $ids = $request->input('ids', null);
        $users = OnlineRegister::whereIn('id', $ids)->get();
        foreach ($users as $index => $user) {
            $signature = getMailSignature($user->user_id);

            $automail = new Automail();
            $automail->template_code = 'registered_course';
            $automail->params = [
                'signature' => $signature,
                'gender' => $user->user->gender=='1'?'Anh':'Chị',
                'full_name' => $user->user->full_name,
                'course_code' => $course->code,
                'course_name' => $course->name,
                'course_type' => 'Online',
                'start_date' => $course->start_date,
                'end_date' => $course->end_date,
                'training_location' => 'Elearning',
                'url' => route('module.online.detail_online', ['id' => $course->id])
            ];
            $automail->users = [$user->user_id];
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course->id;
            $automail->object_type = 'register_approved_online';
            $automail->addToAutomail();
        }

        json_message('Gửi mail thành công','success');
    }
}
