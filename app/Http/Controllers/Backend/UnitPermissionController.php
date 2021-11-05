<?php

namespace App\Http\Controllers\Backend;

use App\Profile;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnitPermissionController extends Controller
{
    public function index() {
        return view('backend.unit_permission.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = UnitPermission::query();
        $query->select(['a.*','b.code AS unit_code', 'b.name AS unit_name', 'c.lastname', 'c.firstname', 'c.code AS user_code']);
        $query->from('el_unit_permission AS a');
        $query->leftJoin('el_unit AS b', 'b.id', '=', 'a.unit_id');
        $query->leftJoin('el_profile AS c', 'c.user_id', '=', 'a.user_id');

        if ($search) {
            $query->where('b.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->unit_code = $row->unit_code;
            $row->unit_name = $row->unit_name;
            $row->name = $row->user_code . ' - ' . $row->lastname . ' ' . $row->firstname;
            $row->edit_url = route('backend.unit_permission.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        if ($id) {
            $model = UnitPermission::find($id);
            $unit = Unit::find($model->unit_id);
            $user = Profile::find($model->user_id);
            $page_title = $unit->name;

            return view('backend.unit_permission.form', [
                'model' => $model,
                'page_title' => $page_title,
                'unit' => $unit,
                'user' => $user,
            ]);
        }
        else {
            $model = new UnitPermission();
            $page_title = trans('backend.add_new');

            return view('backend.unit_permission.form', [
                'model' => $model,
                'page_title' => $page_title,
            ]);
        }

    }

    public function save(Request $request) {
        $this->validateRequest([
            'unit_id' => 'required|exists:el_unit,id',
            'user_id' => 'required',
        ], $request, UnitPermission::getAttributeName());

        $users = $request->user_id;
        $unit_id = $request->unit_id;

        foreach ($users as $user) {
            if (UnitPermission::checkExists($unit_id, $user)) {
                continue;
            }

            $model = UnitPermission::firstOrNew(['id' => $request->id]);
            $model->unit_id = $unit_id;
            $model->user_id = $user;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
            'redirect' => route('backend.unit_permission')
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        UnitPermission::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
