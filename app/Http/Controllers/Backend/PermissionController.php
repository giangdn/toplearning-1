<?php

namespace App\Http\Controllers\Backend;

use App\Permission;
use App\Profile;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PermissionUser;

class PermissionController extends Controller
{
    public function index() {
        return view('backend.permission.index');
    }

    public function listPermisstion() {
        return view('backend.permission.list');
    }

    public function detail($permission_id) {
        $parent = Permission::findOrFail($permission_id);
        $user_added = PermissionUser::getUserPermission($parent->code);
        return view('backend.permission.detail.index', [
            'page_title' => $parent->name,
            'parent' => $parent,
            'user_added' => $user_added
        ]);
    }

    public function formUser($permission_id, $user_id = null, $unit_id = null) {
        $permission = Permission::findOrFail($permission_id);
        $permission_child = Permission::getChildPermission($permission->code);

        if (empty($user_id)) {
            $page_title = trans('backend.add_new');
            return view('backend.permission.detail.form', [
                'permission' => $permission,
                'permission_child' => $permission_child,
                'page_title' => $page_title,
            ]);
        }

        $profile = Profile::findOrFail($user_id);
        $unit = Unit::find($unit_id);
        $page_title = $profile->lastname .' '. $profile->firstname;
        $haspermission = function ($code, $user_id, $unit_id) {
            return PermissionUser::hasPermission($code, $user_id, $unit_id);
        };

        return view('backend.permission.detail.form', [
            'permission' => $permission,
            'permission_child' => $permission_child,
            'page_title' => $page_title,
            'haspermission' => $haspermission,
            'unit' => $unit,
            'profile' => $profile,
        ]);
    }

    public function getDataPermission(Request $request) {
        $search = $request->input('search');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Permission::query();
        $query->whereNull('parent_code');
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('id', 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.permission.detail', ['permission_id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getDataPermissionUser($parent_id, Request $request) {
        $search = $request->input('search');
        $user = $request->input('user');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $permission = Permission::findOrFail($parent_id);
        $permission_child = Permission::getArrayCodeChild($permission->code);
        $prefix = \DB::getTablePrefix();
        $join_sub = PermissionUser::select(['unit_id', 'user_id'])
            ->whereIn('permission_code', $permission_child)
            ->groupBy(['unit_id', 'user_id']);

        $query = \DB::query()
            ->select([
                'a.user_id',
                'a.firstname',
                'a.lastname',
                'a.code',
                'a.title_code',
                'a.unit_code',
                'b.name AS unit',
                'c.name AS title',
                'd.unit_id AS manager_unit_id',
                'e.name AS manager_unit'
            ])
            ->from('el_profile AS a')
            ->join(\DB::raw('('. $join_sub->toSql() .') AS '. $prefix .'d'), 'd.user_id', '=', 'a.user_id')
            ->addBinding($join_sub->getBindings())
            ->leftJoin('el_unit AS b', 'b.code', '=', 'a.unit_code')
            ->leftJoin('el_titles AS c', 'c.code', '=', 'a.title_code')
            ->leftJoin('el_unit AS e', 'e.id', '=', 'd.unit_id');

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }

        if ($user) {
            $query->where('a.user_id', '=', $user);
        }

        $count = $query->count();
        $query->orderBy('a.user_id', $order)
            ->offset($offset)
            ->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->id = $row->user_id .'_'. $row->manager_unit_id;
            $row->edit_url = route('backend.permission.detail.edit', [
                'permission_id' => $parent_id,
                'user_id' => $row->user_id,
                'unit_id' => $row->manager_unit_id]
            );
            $row->permission = PermissionUser::getPermissionNameUser($row->user_id, $row->manager_unit_id, $permission_child);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save($permission_id, Request $request) {
        $this->validateRequest([
            'user_id' => 'required'
        ], $request, ['user_id' => 'Người dùng']);

        $user_ids = $request->user_id;
        $unit_id = empty($request->unit_id) ? 0 : $request->unit_id;
        $permission = Permission::findOrFail($permission_id);
        $permission_child = Permission::getChildPermission($permission->code);

        if (is_array($user_ids)) {
            foreach ($permission_child as $item) {
                $checked = $request->{'permission_' . $item->id};

                if (is_array($user_ids)) {

                    foreach ($user_ids as $user_id) {
                        $permission_exists = PermissionUser::checkExists($item->code, $user_id, $unit_id);
                        if (!$permission_exists && $checked == 1) {
                            $permission_user = new PermissionUser();
                            $permission_user->permission_code = $item->code;
                            $permission_user->user_id = $user_id;
                            $permission_user->unit_id = if_empty($unit_id, 0);
                            $permission_user->save();
                        }

                        if ($permission_exists && $checked == 0) {
                            PermissionUser::where('permission_code', $item->code)
                                ->where('user_id', '=', $user_id)
                                ->where('unit_id', '=', $unit_id)
                                ->delete();
                        }
                    }

                }
            }
        }
        else {
            foreach ($permission_child as $item) {
                $checked = $request->{'permission_' . $item->id};

                $user_id = $user_ids;
                $permission_exists = PermissionUser::checkExists($item->code, $user_id, $unit_id);
                if (!$permission_exists && $checked == 1) {
                    $permission_user = new PermissionUser();
                    $permission_user->permission_code = $item->code;
                    $permission_user->user_id = $user_id;
                    $permission_user->unit_id = if_empty($unit_id, 0);
                    $permission_user->save();
                }

                if ($permission_exists && $checked == 0) {
                    PermissionUser::where('permission_code', $item->code)
                        ->where('user_id', '=', $user_id)
                        ->where('unit_id', '=', $unit_id)
                        ->delete();
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
            'redirect' => route('backend.permission.detail', ['permission_id' => $permission->id])
        ]);
    }

    public function remove($permission_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required'
        ], $request);

        $permission = Permission::findOrFail($permission_id);
        $ids = $request->ids;
        $permissions = Permission::where('parent_code', '=', $permission->code)->pluck('code')->toArray();

        foreach ($ids as $id) {
            $explode = explode('_', $id);
            $user_id = $explode[0];
            $unit_id = $explode[1];
            PermissionUser::where('user_id', '=', $user_id)
                ->where('unit_id', '=', $unit_id)
                ->whereIn('permission_code', $permissions)
                ->delete();
        }

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
            'redirect' => route('backend.permission.detail', ['permission_id' => $permission_id])
        ]);
    }

}
