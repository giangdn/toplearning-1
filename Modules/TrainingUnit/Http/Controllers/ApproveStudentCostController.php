<?php

namespace Modules\TrainingUnit\Http\Controllers;

use App\Automail;
use App\Models\Categories\StudentCost;
use App\Models\Categories\Unit;
use App\Permission;
use App\Models\Categories\UnitManager;
use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterApprove;
use Modules\Offline\Entities\OfflineStudentCostByUser;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineRegisterApprove;

class ApproveStudentCostController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[5]);

        return view('trainingunit::backend.approve_student_cost.index',[
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

        $unit_manager = Permission::isUnitManager();

        //OfflineCourse::addGlobalScope(new DraftScope());
        $query = OfflineCourse::query()
            ->where('enter_student_cost', '=', 1)
            ->where('status', '=', 1);

        if ($unit_manager){
            $unit_user = Unit::find(session('user_unit'));
            $child_arr = Unit::getArrayChild(@$unit_user->code);

            $query->whereIn('id', function ($sub) use ($unit_user, $child_arr){
                $sub->select(['course_id'])
                    ->from('el_offline_register_view')
                    ->orWhere('unit_id', '=', @$unit_user->id)
                    ->orWhereIn('unit_id', $child_arr)
                    ->pluck('course_id')
                    ->toArray();
            });
        }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }

        if ($start_date) {
            $query->where('start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('start_date', '<=', date_convert($end_date));
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->register_deadline = $row->register_deadline ? get_date($row->register_deadline, 'd/m/Y') : null;
            $row->edit_url = route('module.training_unit.approve_student_cost.course', ['id' => $row->id]);

            $count_quantity_approved = OfflineRegister::query();
            $count_quantity_approved->from('el_offline_register as register')
            ->whereIn('register.id', function ($sub){
                $sub->select(['register_id'])
                    ->from('el_offline_student_cost_by_user')
                    ->pluck('register_id')
                    ->toArray();
            })
            ->where('register.course_id', '=', $row->id);
            $count_quantity = $count_quantity_approved->count();
            $row->count_quantity_approved = $count_quantity;

            $count_approved = OfflineRegister::query();
            $count_approved->from('el_offline_register as register')
            ->whereIn('register.id', function ($sub){
                $sub->select(['register_id'])
                    ->from('el_offline_student_cost_by_user')
                    ->where('manager_approved',1)
                    ->pluck('register_id')
                    ->toArray();
            })
            ->where('register.course_id', '=', $row->id);
            $count = $count_approved->count();
            $row->count_approved = $count;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function course($id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[5]);
        
        $course = OfflineCourse::findOrFail($id);
        return view('trainingunit::backend.approve_student_cost.course', [
            'course' => $course,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[5],
        ]);
    }

    public function getDataRegister($id, Request $request) {
        $search = $request->search;
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $unit_manager = Permission::isUnitManager();
        if(!$unit_manager) {
            OfflineRegister::addGlobalScope(new DraftScope());
        }

        $query = OfflineRegister::query();
        $query->select([
            "register.id",
            'profile.code',
            'profile.lastname',
            'profile.firstname',
            'profile.email',
            'unit.name AS unit_name',
            'unit_manager.name AS unit_manager',
            'title.name AS title_name',
        ]);
        $query->from('el_offline_register as register')
            ->join('el_profile AS profile', 'profile.user_id', '=', "register.user_id")
            ->leftJoin('el_unit AS unit', 'unit.code', '=', 'profile.unit_code')
            ->leftJoin('el_unit AS unit_manager', 'unit_manager.code', '=', 'unit.parent_code')
            ->leftJoin('el_titles AS title', 'title.code', '=', 'profile.title_code')
            ->whereIn('register.id', function ($sub){
                $sub->select(['register_id'])
                    ->from('el_offline_student_cost_by_user')
                    ->pluck('register_id')
                    ->toArray();
            })
            ->where('register.course_id', '=', $id);

        if ($unit_manager){
            $unit_user = Unit::find(session('user_unit'));
            $child_arr = Unit::getArrayChild(@$unit_user->code);

            $query->where(function ($sub) use ($unit_user, $child_arr){
                $sub->orWhere('unit.id', '=', @$unit_user->id);
                $sub->orWhereIn('unit.id', $child_arr);
            });
        }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('profile.code', 'like', '%'. $search .'%');
                $subquery->orWhere('profile.lastname', 'like', '%'. $search .'%');
                $subquery->orWhere('profile.firstname', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $manager_approved = OfflineStudentCostByUser::whereRegisterId($row->id)->first();
            $row->approved = $manager_approved->manager_approved;
            switch ($manager_approved->manager_approved){
                case 1: $row->manager_approved = 'Đã duyệt'; break;
                case 0: $row->manager_approved = 'Từ chối'; break;
                default: $row->manager_approved = 'Chưa duyệt'; break;
            }
            $sum_cost = OfflineStudentCostByUser::whereRegisterId($row->id)->sum('cost');
            $row->sum_cost = if_empty(number_format($sum_cost, 0), '0');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getModalStudentCost($course_id, Request $request) {
        $this->validateRequest([
            'regid' => 'required',
        ], $request);

        $register = OfflineRegister::find($request->regid);
        $full_name = Profile::fullname($register->user_id);
        $student_costs = StudentCost::where('status','=',1)->get();
        $register_cost = OfflineStudentCostByUser::where('register_id', '=', $request->regid)->get();
        $total_student_cost = OfflineStudentCostByUser::getTotalStudentCost($request->regid, $course_id);
        $manager_approved = $request->approved;
        return view('trainingunit::backend.modal.student_cost_by_user', [
            'regid' => $request->regid,
            'student_costs' => $student_costs,
            'register_cost' => $register_cost,
            'total_student_cost' => $total_student_cost,
            'course_id' => $course_id,
            'register' => $register,
            'full_name' => $full_name,
            'manager_approved' => $manager_approved,
        ]);
    }

    public function approved($course_id, Request $request) {
        $this->validateRequest([
            'regid' => 'required',
            'status' => 'required'
        ], $request, ['ids' => 'Học viên', 'status' => 'Trạng thái']);

        $regid = $request->regid;
        $status = $request->status;
        OfflineStudentCostByUser::whereRegisterId($regid)
            ->update([
                'manager_approved' => $status
            ]);

        if ($status == 1){
            $sql = OfflineStudentCostByUser::selectRaw('register_id, cost_id, cost, note, now(), now()')->where('register_id', '=', $regid);

            DB::table('el_offline_student_cost')->insertUsing(['register_id', 'cost_id', 'cost', 'note', 'created_at', 'updated_at'], $sql);
        }

        json_message('Xét duyệt thành công');
    }
}
