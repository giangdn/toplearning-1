<?php

namespace Modules\User\Http\Controllers\Backend;

use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\User\Entities\WorkingProcess;

class WorkingProcessController extends Controller
{
    public function index($user_id) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $full_name = Profile::fullname($user_id);
        return view('user::backend.working_process.index', [
            'user_id' => $user_id,
            'full_name' => $full_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData($user_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = WorkingProcess::query();
        $query->select([
            'a.*',
            'b.name as title_name',
            'c.name as unit_name',
            'd.email'
        ]);
        $query->from('el_working_process as a');
        $query->leftJoin('el_titles as b', 'b.code', '=', 'a.title_code');
        $query->leftJoin('el_unit as c', 'c.code', '=', 'a.unit_code');
        $query->leftJoin('el_unit as e', 'e.code', '=', 'c.parent_code');
        $query->leftJoin('el_profile as d', 'd.user_id', '=', 'a.user_id');
        $query->where('a.user_id', '=', $user_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->code = Profile::usercode($user_id);
            $row->fullname = Profile::fullname($user_id);
            $row->edit_url = route('module.backend.working_process.edit', ['user_id' => $user_id, 'id' => $row->id]);
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($user_id, $id = 0, Request $request) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $full_name = Profile::fullname($user_id);

        if ($id){
            $model = WorkingProcess::find($id);
            $title = Titles::where('code', '=', $model->title_code)->first();
            $unit = Unit::where('code', '=', $model->unit_code)->first();
            return view('user::backend.working_process.form', [
                'model' => $model,
                'user_id' => $user_id,
                'full_name' => $full_name,
                'title' => $title,
                'unit' => $unit,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }
        else{
            $model = new WorkingProcess();
            return view('user::backend.working_process.form', [
                'model' => $model,
                'user_id' => $user_id,
                'full_name' => $full_name,
                'get_menu_child' => $get_menu_child,
                'name_url' => $get_name_url[4],
            ]);
        }
    }

    public function save($user_id, Request $request) {
        $this->validateRequest([
            'title_id' => 'required|exists:el_titles,id',
            'unit_id' => 'required|exists:el_unit,id',
        ],$request, WorkingProcess::getAttributeName());

        $unit = Unit::where('id', '=', $request->unit_id)->first();
        $title = Titles::where('id', '=', $request->title_id)->first();

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($end_date && $end_date < $start_date){
            json_message('Ngày bắt đầu phải trước ngày kết thúc', 'error');
        }

        $model = WorkingProcess::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->user_id = $user_id;
        $model->unit_code = $unit->code;
        $model->title_code = $title->code;
        $model->start_date = date_convert($start_date);
        if ($end_date) {
            $model->end_date = date_convert($end_date);
        }
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.backend.working_process', ['user_id' => $user_id])
        ]);
    }

    public function remove($user_id, Request $request) {
        $ids = $request->input('ids', null);
        WorkingProcess::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
