<?php

namespace Modules\User\Http\Controllers\Backend;

use App\Models\Categories\Area;
use App\Certificate;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\User;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineRegister;
use App\Http\Controllers\Controller;
use Modules\User\Entities\ManagerLevel;

class ManagerController extends Controller
{
    public function index() {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $max_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };

        return view('user::backend.manager_level.index', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'max_area' => $max_area,
            'level_name_area' => $level_name_area,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->input('unit');
        $title = $request->input('title');
        $area = $request->input('area');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Profile::query();
        $query->select([
            'a.id',
            'a.user_id',
            'a.code',
            'a.firstname',
            'a.lastname',
            'b.name AS unit_name',
            'c.name AS title_name',
            'd.name AS area_name',
            'e.name AS unit_manager',
        ]);
        $query->from('el_profile AS a');
        $query->leftJoin('el_unit AS b', 'b.code', '=', 'a.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'b.parent_code');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'a.title_code');
        $query->leftJoin('el_area AS d', 'd.code', '=', 'a.area_code');
        $query->where('a.user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('a.status', '=', $status);
        }

        if ($unit) {
            $unit = Unit::where('id', '=', $unit)->first();
            $query->where('a.unit_code', '=', $unit->code);
        }

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('a.title_code', '=', $title->code);
        }

        if ($area){
            $area = Area::where('id', '=', $area)->first();
            $query->where('a.area_code', '=', $area->code);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.backend.manager_level.edit', ['id' => $row->user_id]);
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);
            $row->area_url = route('module.backend.user.get_area', ['user_id' => $row->user_id]);
            $row->manager_url = route('module.backend.manager_level.get_manager', ['user_id' => $row->user_id]);

            $manager_level = ManagerLevel::query()
                ->where('user_id', '=', $row->user_id)
                ->where('status', '=', 1)
                ->orderBy('level', 'asc')
                ->first('approve');

            if ($manager_level){
                $row->approve = $manager_level->approve;
            }else{
                $row->approve = '';
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id, Request $request) {
        $model = Profile::findOrFail($id);
        $page_title = $model->lastname .' '. $model->firstname;
        $users = Profile::where('user_id', '>', 2)->where('user_id', '!=', $id)->get();

        $manager_level = $this->getItems($id, $request);

        return view('user::backend.manager_level.form', [
            'model' => $model,
            'page_title' => $page_title,
            'users' => $users,
            'manager_level' => $manager_level,
        ]);
    }

    public function getItems($id, Request $request){
        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');

        $query = ManagerLevel::query()->where('user_id', '=', $id);

        if ($fromdate) {
            $query->where('start_date', '>=', date_convert($fromdate, '00:00:00'));
        }

        if ($todate) {
            $query->where('start_date', '<=', date_convert($todate, '23:59:59'));
        }

        $query->orderByDesc('created_at');
        $items = $query->paginate(20);
        $items->appends($request->query());

        return $items;
    }

    public function save(Request $request) {
        $this->validateRequest([
            'user_id' => 'required|exists:el_profile,user_id',
            'user_manager_id' => 'required|exists:el_profile,user_id',
            'start_date' => 'required',
        ],$request, ManagerLevel::getAttributeName());

        $level = $request->level;
        $user_managers = $request->user_manager_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user = $request->user_id;

        if ($end_date && $end_date < $start_date){
            json_message('Ngày bắt đầu phải trước ngày kết thúc', 'error');
        }

        $model = new ManagerLevel();
        $model->user_id = $user;
        $model->user_manager_id = $user_managers;
        $model->level = $level;
        $model->start_date = date_convert($start_date);
        if ($end_date) {
            $model->end_date = date_convert($end_date);
        }
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.backend.manager_level.edit', ['id' => $user])
        ]);
    }

    public function remove(Request $request) {
        $manager_level = ManagerLevel::find($request->manager_id);

        if ($manager_level){
            $manager_level->delete();
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function getManagerByUser(Request $request){
        $user = Profile::find($request->user_id);
        $manager_level = ManagerLevel::query()
            ->where('user_id', '=', $user->user_id)
            ->where('status', '=', 1)
            ->orderBy('level', 'asc')
            ->get();

        $manager = function ($user_manager_id){
            return Profile::find($user_manager_id);
        };

        return view('user::backend.modal.manager_by_user', [
            'manager_level' => $manager_level,
            'user' => $user,
            'manager' => $manager,
        ]);
    }

    public function status(Request $request) {
        $status = $request->status;
        $manager_level = ManagerLevel::find($request->manager_id);
        $manager_level->status = $status;
        $manager_level->save();

        if ($status == 1){
            json_message('Đã mở');
        }
        json_message('Đã ẩn');

    }

    public function approve(Request $request) {
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        foreach ($ids as $id) {
            $query = ManagerLevel::query();
            $query->where('user_id', $id);
            $query->update(['approve' => $status]);
        }

        if ($status == 1){
            json_message('Duyệt thành công','success');
        }
        json_message('Đã từ chối','success');
    }

    public function changeManager(Request $request) {
        $manager_level = ManagerLevel::find($request->manager_id);
        $manager_level->user_manager_id = $request->user_manager;
        $manager_level->save();
        json_message('Thay đổi quản lý thành công');
    }

    public function changeLevel(Request $request) {
        $manager_level = ManagerLevel::find($request->manager_id);
        $manager_level->level = $request->level;
        $manager_level->save();
        json_message('Thay đổi cấp độ thành công');
    }

    public function changeStartDate(Request $request) {
        $manager_level = ManagerLevel::find($request->manager_id);
        if ($manager_level->end_date && $manager_level->end_date < date_convert($request->start_date)){
            json_message('Ngày bắt đầu phải trước ngày kết thúc', 'error');
        }
        $manager_level->start_date = date_convert($request->start_date);
        $manager_level->save();
        json_message('Thay đổi ngày bắt đầu thành công');
    }

    public function changeEndDate(Request $request) {
        $manager_level = ManagerLevel::find($request->manager_id);
        if ($manager_level->start_date > date_convert($request->end_date)){
            json_message('Ngày bắt đầu phải trước ngày kết thúc', 'error');
        }
        $manager_level->end_date = date_convert($request->end_date);
        $manager_level->save();
        json_message('Thay đổi ngày kết thúc thành công');
    }

}
