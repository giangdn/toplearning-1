<?php

namespace App\Http\Controllers\Backend;

use App\PermissionGroup;
use App\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PermissionGroupController extends Controller
{
    public function index() {
        return view('backend.permission_group.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = PermissionGroup::query();
        $query->from('el_permission_group AS a');
        if ($search) {
            $query->where('a.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_date = get_date($row->created_at, 'H:i d/m/Y');
            $row->created_name = Profile::fullname($row->created_by);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required|max:150'
        ], $request);

        $model = PermissionGroup::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->created_by = Auth::id();
        $model->updated_by = Auth::id();
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
            ]);
        }

        json_message('Không thể thêm nhóm quyền', 'error');
    }

    public function getJson(Request $request) {
        if ($request->id) {
            $query = PermissionGroup::query();
            $query->where('id', '=', $request->id);
            if ($query->exists()) {
                json_result($query->first());
            }
        }

        json_message('Nhóm quyền không tồn tại');
    }

    public function remove(Request $request) {
        $this->validateRequest([
            'id' => 'required'
        ], $request);


    }
}
