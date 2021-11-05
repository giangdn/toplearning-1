<?php

namespace Modules\TrainingUnit\Http\Controllers;

use App\Automail;
use App\Permission;
use App\Models\Categories\UnitManager;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterApprove;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineRegisterApprove;

class ApproveCourseController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[5]);

        return view('trainingunit::backend.approve_course.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[5],
        ]);
    }

    public function getData(Request $request) {
        $search = $request->search;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $managers = UnitManager::getIdUnitManagedByUser(\Auth::id());
        $query = \DB::query();
        $query->select([
            'course.id',
            'course.name AS course_name',
            'course.code AS course_code',
            'course.start_date',
            'course.end_date',
            'course.register_deadline',
            \DB::raw('2 AS type')
        ])
            ->from('el_offline_course AS course')
            ->leftJoin('el_offline_register AS register', 'register.course_id', '=', 'course.id')
            ->leftJoin('el_profile AS profile', 'profile.user_id', '=', 'register.user_id')
            ->leftJoin('el_unit AS unit', 'unit.code', '=', 'profile.unit_code')
            ->leftJoin('el_offline_register_approve AS approve', 'approve.register_id', '=', 'register.id')
            ->where('course.status', '=', 1);

            if (!Permission::isAdmin()){
                $query->whereIn('unit.id', $managers);
            }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('course.code', 'like', '%'. $search .'%');
                $subquery->orWhere('course.name', 'like', '%'. $search .'%');
            });
        }

        if ($start_date) {
            $query->where('course.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('course.start_date', '<=', date_convert($end_date));
        }

        $offline = $query;

        $query = \DB::query();
        $query->select([
            'course.id',
            'course.name AS course_name',
            'course.code AS course_code',
            'course.start_date',
            'course.end_date',
            'course.register_deadline',
            \DB::raw('1 AS type')
        ])
            ->from('el_online_course AS course')
            ->leftJoin('el_online_register AS register', 'register.course_id', '=', 'course.id')
            ->leftJoin('el_profile AS profile', 'profile.user_id', '=', 'register.user_id')
            ->leftJoin('el_unit AS unit', 'unit.code', '=', 'profile.unit_code')
            ->leftJoin('el_online_register_approve AS approve', 'approve.register_id', '=', 'register.id')
            ->where('course.status', '=', 1);
            if (!Permission::isAdmin()){
                $query->whereIn('unit.id', $managers);
            }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('course.code', 'like', '%'. $search .'%');
                $subquery->orWhere('course.name', 'like', '%'. $search .'%');
            });
        }

        if ($start_date) {
            $query->where('course.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('course.start_date', '<=', date_convert($end_date));
        }

        $query->union($offline);

        $querySql = $query->toSql();
        $query = \DB::table(\DB::raw("($querySql) as a"))->mergeBindings($query);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->register_deadline = $row->register_deadline ? get_date($row->register_deadline, 'd/m/Y') : null;
            $row->edit_url = route('module.training_unit.approve_course.course', ['id' => $row->id, 'type' => $row->type]);

            $row->type = $row->type == 1 ? 'Offline' : 'Tập trung';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function course($id, $type) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[5]);

        $course = $type == 1 ? OnlineCourse::findOrFail($id) : OfflineCourse::findOrFail($id);
        $model = $type == 1 ? 'el_online_register_approve':'el_offline_register_approve';
        $model_register = $type == 1 ? 'el_online_register':'el_offline_register';
        return view('trainingunit::backend.approve_course.course', [
            'course' => $course,
            'type' => $type,
            'model' => $model,
            'model_register' => $model_register,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[5],
        ]);
    }

    public function getDataRegister($id, $type, Request $request) {
        $search = $request->search;
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $dbprefix = \DB::getTablePrefix();
        $managers = UnitManager::getIdUnitManagedByUser(\Auth::id());

        if ($type==1) {
//            OnlineRegister::addGlobalScope(new DraftScope());
            $query = OnlineRegister::query();
            $table = 'el_online_register';
            $course = 'online';
        }
        else {
//            OfflineRegister::addGlobalScope(new DraftScope());
            $query = OfflineRegister::query();
            $table = 'el_offline_register';
            $course = 'offline';
        }
        $query->select([
            "{$table}.id", 'profile.code', 'profile.lastname', 'profile.firstname','profile.email',
            'unit.name AS unit_name',
            'title.name AS title_name',
            "{$table}.status",
            'parent_unit.name AS parent_name',
            "{$table}.approved_step",
            "{$table}.status",
        ]);
        $query->join("el_{$course}_course AS course", 'course.id', '=', "{$table}.course_id")
            ->join('el_profile AS profile', 'profile.user_id', '=', "{$table}.user_id")
            ->leftJoin('el_unit AS unit', 'unit.code', '=', 'profile.unit_code')
            ->leftJoin('el_unit AS parent_unit', 'parent_unit.code', '=', 'unit.parent_code')
            ->leftJoin('el_titles AS title', 'title.code', '=', 'profile.title_code')
            ->where('course.status', '=', 1)
            ->where('course.id', '=', $id);

            if (!Permission::isAdmin()){
                $query->whereIn('unit.id', $managers);
            }

        if ($search) {
            $query->where(function ($subquery) use ($search, $dbprefix) {
                $subquery->orWhere('profile.code', 'like', '%'. $search .'%');
                $subquery->orWhere(\DB::raw("CONCAT({$dbprefix}profile.lastname, ' ', {$dbprefix}profile.firstname)"), 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->unit_status = is_null($row->unit_status) ? 2 : $row->unit_status;
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
            } else {
                $register = OfflineRegister::find($id);
            }

            if (empty($register)) {
                continue;
            }

            if ($type == 1) {
                $model = OnlineRegisterApprove::firstOrNew(['register_id' => $id]);

            } else {
                $model = OfflineRegisterApprove::firstOrNew(['register_id' => $id]);
            }

            $model->register_id = $id;
            $model->course_id = $register->course_id;
            $model->user_id = $register->user_id;
            $model->approve_by = \Auth::id();
            $model->status = $status;
            $model->save();

            $register->status = $status;
            $register->save();

            if ($status == 1) {
                $permission_code = $course->unit_id > 0 ? 'module.training_unit.online.register.approve': 'module.online.register.approve';
                $users = Permission::getUserPermission($permission_code, intval($course->unit_id));
                foreach ($users as $user_id){
                    $signature = getMailSignature($user_id);
                    $automail = new Automail();
                    $automail->template_code = 'approve_register';
                    $automail->params = [
                        'signature' => $signature,
                        'code' => $course->code,
                        'name' => $course->name,
                        'start_date' => $course->start_date,
                        'end_date' => $course->end_date,
                        'url' => route('module.training_unit.approve_course.course', ['id' => $course_id, 'type' => $type])
                    ];

                    $automail->users = [$user_id];
                    $automail->object_id = $course->id;
                    $automail->object_type = 'approve_'. ($type==1?'online':'offline') .'_register';
                    $automail->addToAutomail();
                }

            }

        }

        json_message('Xét duyệt thành công');
    }
}
