<?php

namespace App\Http\Controllers\Mobile;

use App\Automail;
use App\Http\Controllers\Controller;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterApprove;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineRegisterApprove;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\User\Entities\TrainingProcess;

class ApproveCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $list = $this->getListCourse($request);
        return view('themes.mobile.frontend.approve_course.course', [
            'list' => $list,
        ]);
    }

    public function course(Request $request, $id, $type){
        $course = $type == 1 ? OnlineCourse::findOrFail($id) : OfflineCourse::findOrFail($id);
        return view('themes.mobile.frontend.approve_course.user', [
            'users' => $this->getUser($request, $id, $type),
            'course' => $course,
            'type' => $type,
        ]);
    }

    public function getUser(Request $request, $id, $type){
        $managers =  UnitManager::getIdUnitManagedByUser();
        $table = $type == 1 ? 'online': 'offline';

        $query = \DB::query();
        $query->select([
            'register.id',
            'profile.code',
            'profile.lastname',
            'profile.firstname',
            'unit.name AS unit_name',
            'title.name AS title_name',
            'register.status AS unit_status',
            'parent_unit.name AS parent_name'
        ]);
        $query->from("el_{$table}_register AS register")
            ->join("el_{$table}_course AS course", 'course.id', '=', 'register.course_id')
            ->join('el_profile AS profile', 'profile.user_id', '=', 'register.user_id')
            ->leftJoin('el_unit AS unit', 'unit.code', '=', 'profile.unit_code')
            ->leftJoin('el_unit AS parent_unit', 'parent_unit.code', '=', 'unit.parent_code')
            ->leftJoin('el_titles AS title', 'title.code', '=', 'profile.title_code')
            ->leftJoin("el_{$table}_register_approve AS approve", 'approve.register_id', '=', 'register.id')
            ->where('course.status', '=', 1)
            ->where('course.id', '=', $id);

        return $query->get();
    }

    public function getListCourse(Request $request)
    {
        $search = $request->get('q');
        $type = $request->get('type');

        $managers =  UnitManager::getIdUnitManagedByUser();

        OfflineCourse::addGlobalScope(new DraftScope());
        $query = DB::query();
        $query->select([
            'id',
            'name AS course_name',
            'code AS course_code',
            'start_date',
            'end_date',
            'register_deadline',
            'created_at',
            \DB::raw('2 AS type'),
        ])
            ->from('el_offline_course')
            ->where('status', '=', 1);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_offline_course.code', 'like', '%'. $search .'%');
                $subquery->orWhere('el_offline_course.name', 'like', '%'. $search .'%');
            });
        }

        if ($type && $type == 1){
            $query->whereIn('id',function ($sub){
                $sub->select(['course_id'])
                    ->from('el_offline_register')
                    ->where('status', '!=', 1);
            });
        }

        if ($type && $type == 2){
            $query->whereIn('id', function ($sub){
                $sub->select(['course_id'])
                    ->from('el_offline_register')
                    ->where('status', '=', 1);
            });
        }
        if ($type && $type == 3){
            $query->where(function ($subquery){
                $subquery->whereNotNull('el_offline_course.end_date');
                $subquery->where('el_offline_course.end_date', '<', date('Y-m-d H:i:s'));
            });
        }

        $offline = $query;

        OnlineCourse::addGlobalScope(new DraftScope());
        $query = DB::query();
        $query->select([
            'id',
            'name AS course_name',
            'code AS course_code',
            'start_date',
            'end_date',
            'register_deadline',
            'created_at',
            \DB::raw('1 AS type'),
        ])
            ->from('el_online_course')
            ->where('status', '=', 1);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_online_course.code', 'like', '%'. $search .'%');
                $subquery->orWhere('el_online_course.name', 'like', '%'. $search .'%');
            });
        }
        if ($type && $type == 1){
            $query->whereIn('id', function ($sub){
                $sub->select(['course_id'])
                    ->from('el_online_register')
                    ->where('status', '!=', 1);
            });
        }

        if ($type && $type == 2){
            $query->whereIn('id', function ($sub){
                $sub->select(['course_id'])
                    ->from('el_online_register')
                    ->where('status', '=', 1);
            });
        }

        if ($type && $type == 3){
            $query->where(function ($subquery){
                $subquery->whereNotNull('el_online_course.end_date');
                $subquery->where('el_online_course.end_date', '<', date('Y-m-d H:i:s'));
            });
        }

        if ($offline){
            $query->union($offline);
            $querySql = $query->toSql();
            $query = \DB::table(\DB::raw("($querySql) as a"))->mergeBindings($query);
        }

//dd($query->get());
        return $query->get();
    }

    public function approveCourse($course_id, $type, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required'
        ], $request, ['ids' => 'Học viên', 'status' => 'Trạng thái']);

        if ($type == 1) {
            $course = OnlineCourse::findOrFail($course_id);
        }
        else {
            $course = OfflineCourse::findOrFail($course_id);
        }

        $ids = $request->ids;
        $status = $request->status;

        foreach ($ids as $id) {
            if ($type == 1) {
                $register = OnlineRegister::find($id);
            }
            else {
                $register = OfflineRegister::find($id);
            }

            if (empty($register)) {
                continue;
            }

            $user_id = $register->user_id;

            if ($type == 1) {
                $model = OnlineRegisterApprove::firstOrNew(['register_id' => $id]);
                (new ApprovedModelTracking())->updateApprovedTracking(OnlineRegister::getModel(),$id,$status,'');
            }
            else {
                $model = OfflineRegisterApprove::firstOrNew(['register_id' => $id]);
                (new ApprovedModelTracking())->updateApprovedTracking(OfflineRegister::getModel(),$id,$status,'');
            }

            $model->register_id = $id;
            $model->course_id = $register->course_id;
            $model->user_id = $register->user_id;
            $model->approve_by = \Auth::id();
            $model->status = $status;
            $model->save();

            TrainingProcess::where(['user_id'=>$user_id,'course_id'=>$course_id,'course_type'=>$type])->update(['status'=>$status]);

            if ($status == 1) {
                //$permission_code = $course->unit_id > 0 ? 'module.training_unit.online.register.approve': 'module.online.register.approve';
                $users = [2];
                $automail = new Automail();
                $automail->template_code = 'approve_register';
                $automail->params = [
                    'code' => $course->code,
                    'name' => $course->name,
                    'start_date' => $course->start_date,
                    'end_date' => $course->end_date,
                    'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => $type])
                ];

                $automail->users = $users;
                $automail->object_id = $course->id;
                $automail->object_type = 'approve_'. ($type==1?'online':'offline') .'_register';
                $automail->addToAutomail();
            }

        }

        json_message('Xét duyệt thành công');
    }
}
