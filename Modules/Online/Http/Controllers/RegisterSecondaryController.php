<?php

namespace Modules\Online\Http\Controllers;

use App\Automail;
use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
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
use Modules\Online\Entities\OnlineRegisterApprove;
use Modules\Online\Entities\OnlineResult;
use Modules\Online\Imports\RegisterImport;
use Modules\Online\Imports\RegisterSecondaryImport;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\User\Entities\TrainingProcess;

class RegisterSecondaryController extends Controller
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

        return view('online::backend.register_secondary.index', [
            'online' => $online,
            'quiz_exists' => $quiz_exists,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function getData($course_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineRegister::query();
        $query->select([
            'a.*',
            'b.code',
            'b.name',
            'b.email',
        ]);

        $query->from('el_online_register AS a');
        $query->leftJoin('el_quiz_user_secondary as b', 'b.id', '=', 'a.user_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.user_type', '=', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('b.name', 'like', '%' . $search . '%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $quiz_register = QuizRegister::where('user_id', '=', $row->user_id)->where('type', '=', 2)->get();

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
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataNotRegister($course_id, Request $request){
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = QuizUserSecondary::query();
        $query->select(['*']);
        $query->whereNotIn('id', function($sub_query) use ($course_id) {
            $sub_query->select(['user_id']);
            $sub_query->from('el_online_register');
            $sub_query->where('course_id', '=', $course_id);
            $sub_query->where('user_type', '=', 2);
        });

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($course_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $online = OnlineCourse::findOrFail($course_id);
        return view('online::backend.register_secondary.form', [
            'online' => $online,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function save($course_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, OnlineRegister::getAttributeName());

        $course = OnlineCourse::findOrFail($course_id);
        $ids = $request->input('ids', null);
        $subject = Subject::findOrFail($course->subject_id);

        foreach($ids as $id){
            OnlineRegister::updateOrCreate([
                'user_id' => $id,
                'user_type' => 2,
                'course_id' => $course_id,
            ], [
                'status' => 1,
            ]);

            // update training process
            TrainingProcess::updateOrCreate(
                [
                    'user_id'=>$id,
                    'user_type'=>2,
                    'course_id'=>$course_id,
                    'course_type'=>1
                ],
                [
                    'course_code'=>$course->code,
                    'course_name'=>$course->name,
                    'subject_id'=>$subject->id,
                    'subject_code'=>$subject->code,
                    'subject_name'=>$subject->name,
                    'start_date'=>$course->start_date,
                    'end_date'=>$course->end_date,
                    'process_type'=>1,
                    'certificate'=>$course->cert_code,
                ]
            );
            /////////////////

            $quizs = Quiz::where('course_id', '=', $course_id)->where('status', '=', 1)->get();
            if ($quizs){
                foreach ($quizs as $quiz){
                    $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
                    if ($quiz_part){
                        QuizRegister::query()
                            ->updateOrCreate([
                                'quiz_id' => $quiz->id,
                                'user_id' => $id,
                                'type' => 2,
                            ],[
                                'part_id' => $quiz_part->id,
                            ]);
                    }else{
                        continue;
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
                ->where('b.type', '=', 2)
                ->get();
            if (count($quizs) > 0){
                $count = 0;
                foreach ($quizs as $quiz){
                    $result = QuizResult::where('quiz_id', '=', $quiz->id)
                        ->where('user_id', '=', $quiz->user_id)
                        ->where('type', '=', 2)
                        ->first();
                    if ($result){
                        $count++;
                        continue;
                    }else{
                        QuizRegister::where('quiz_id', '=', $quiz->id)
                            ->where('user_id', '=', $quiz->user_id)
                            ->where('type', '=', 2)
                            ->delete();
                    }
                }
                if ($count == 0){
                    $online_register->delete();
                }
            }else{
                $online_register->delete();
            }

            $online_course = OnlineCourse::find($online_register->course_id);
            TrainingProcess::where([
                'user_id' => $online_register->user_id,
                'user_type' => 2,
                'course_id' => $online_course->id,
                'course_type' => 2
            ])->delete();
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

        $import = new RegisterSecondaryImport($course_id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.online.register_secondary', ['id' => $course_id]);

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
            $full_name = QuizUserSecondary::find($register->user_id);

            $result = QuizResult::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $register->user_id)
                ->where('type', '=', 2)
                ->first();

            if ($result){
                $errors[] = "Nhân viên <b>$full_name</b> đã thi. Không thể sửa";
                continue;
            }

            QuizRegister::query()
                ->updateOrCreate([
                    'quiz_id' => $quiz_id,
                    'user_id' => $register->user_id,
                    'type' => 2,
                ],[
                    'part_id' => $part_id,
                ]);
        }

        session()->put('errors', $errors);
        session()->save();

        json_message(trans('lageneral.successful_save'));
    }

    public function sendMailUserRegisted($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request,[
            'ids' => 'Học viên',
        ]);

        $course = OnlineCourse::find($course_id);
        $ids = $request->input('ids', null);
        $users = OnlineRegister::whereIn('id', $ids)->where('user_type', 2)->get();
        foreach ($users as $index => $user) {
            $signature = getMailSignature($user->user_id, 2);

            $user_second = QuizUserSecondary::find($user->user_id);
            $automail = new Automail();
            $automail->template_code = 'registered_course';
            $automail->params = [
                'signature' => $signature,
                'gender' => 'Anh/Chị',
                'full_name' => $user_second->name,
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
