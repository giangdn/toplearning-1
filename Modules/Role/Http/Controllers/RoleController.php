<?php

namespace Modules\Role\Http\Controllers;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Exports\PermissionExport;
use App\PermissionType;
use App\Profile;
use App\Role;
use App\RolePermissionType;
use App\Scopes\DraftScope;
use App\User;
use App\UserPermissionType;
use App\UserRole;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Modules\Role\Entities\RoleHasPermission;
use Modules\Role\Entities\RoleHasPermissionType;
use Modules\Role\Entities\TitleRole;

class RoleController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('role::index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function create()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $action = trans('backend.add_new');
        $role = new Role();
        return view('role::form',[
            'action' =>$action,
            'role'  =>$role,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function store(Request $request)
    {
        $this->validateRequest([
            'id' => 'nullable|exists:el_roles,id',
            'code' => 'required_without:id|max:255|unique:el_roles,code,'.$request->id,
            'name' => 'required_without:id|max:255|unique:el_roles,name,'.$request->id,
            'description' => 'required|max:255',
        ], $request, Role::getAttributeName());
        $model = Role::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->type = ($model->type ? $model->type : 2);
        $model->guard_name = 'web';
        if (empty($request->id)) {
            $model->created_by = \Auth::id();
        }
        $model->updated_by = \Auth::id();
        if($model->save())
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('backend.roles')
            ]);
        else
            json_message(trans('lageneral.save_error'), 'error');
    }

    public function edit($id)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $action = trans('backend.update');
        $role = Role::find($id);
        return view('role::form',[
            'role'=>$role,
            'action'=>$action,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        Role::addGlobalScope(new DraftScope());
        $query = Role::query();
        $query->leftJoin('el_role_has_permission_type as b','b.role_id','el_roles.id');
        $query->leftJoin('el_permission_type as c','c.id','b.permission_type_id');
        $query->where('el_roles.type', '!=', 1);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get(['el_roles.*','c.name as group_permission','b.permission_type_id']);
        foreach ($rows as $row){
            if ($row->type == 1){
                $row->created_by = trans('backend.default');
                $row->updated_by = trans('backend.default');
            }else{
                $getCreatedBy = Profile::find($row->created_by);
                $getUpdatedBy = Profile::find($row->updated_by);

                $row->created_by = (@$getCreatedBy->lastname . ' ' . @$getCreatedBy->firstname);
                $row->updated_by = (@$getUpdatedBy->lastname . ' ' . @$getUpdatedBy->firstname);
            }

            $row->user_role = route('backend.roles.user.assign_role', ['role' => $row->id]);
            $row->title_role = route('backend.roles.title.assign_role', ['role' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function delete(Request $request)
    {
        $role_id = $request->ids[0];
        $role = Role::where(['type'=>2,'id'=>$role_id])->first();
        if (!$role){
            json_message('Lỗi: role không hợp lệ');
        }
        $usersRole = User::select(['id'])->whereHas('roles', function ( $query ) use ( $role ) {
            $query->where( 'id', $role->id );
        })->get();
        $rolePermission = \Spatie\Permission\Models\Role::findById($role_id)->permissions;
        foreach ($usersRole as $index => $userRole) {
            User::find($userRole->id)->revokePermissionTo($rolePermission);
            UserPermissionType::where('user_id',$userRole->id)->delete();
        }

        Role::query()->where('type','=',2)->where('id','=',$role_id)->delete();
        RoleHasPermissionType::where('role_id',$role_id)->delete();
        RolePermissionType::where('role_id',$role_id)->delete();
        UserRole::where('role_id',$role_id)->delete();
        RoleHasPermission::where('role_id',$role_id)->delete();
        \DB::table('el_model_has_roles')->where(['role_id'=>$role_id])->delete();
        json_message('Đã xóa thành công');
    }

    public function getPermission($role, Request $request) {
        $sort = $request->input('sort', 'id');
//        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $search = $request->input('search','');
        $query = \DB::query()->select([
            'a.*',
            'b.*',
            'c.permission_type_id'
        ])
            ->from('el_permissions as a')
            ->leftJoin('el_role_has_permissions as b', function($join) use ($role){
                $join->on('a.id','=','b.permission_id')
                    ->where('b.role_id','=',$role);
            })
            ->leftJoin('el_role_permission_type as c',function ($join) use($role){
                $join->on('c.permission_id','=','a.id')
                    ->where('c.role_id','=',$role);
            });
        $query->whereNotNull('a.model')->whereNull('extend');
        if ($search){
            $query->where(function (Builder $_where) use ($search){
                $_where->where('a.name','like','%'.$search.'%');
                $_where->orWhere('a.description','like','%'.$search.'%');
            });
        }
        $count = $query->count();
//        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        $permission_type = PermissionType::query()->select(['id','name','type','description'])->where('type', '!=', 1)->get();
        $group_permission = RoleHasPermissionType::where(['role_id'=>$role])->value('permission_type_id');
        foreach ($rows as $row){
            $row->group_permission = $group_permission;
            $row->permission_type = $permission_type;
            $row->permission = \DB::query()->select(['a.id','a.name','a.description','b.permission_id'])
                ->from('el_permissions as a')
                ->leftJoin('el_role_has_permissions as b', function ($join) use($role){
                    $join->on( 'a.id','=','b.permission_id')
                        ->where('b.role_id','=',$role);
                })
                ->where('a.parent','=',$row->name) ->get();
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function savePermissionByRole(Request $request)
    {
        $ids_check = $request->ids;
        $ids_uncheck = $request->ids_uncheck;
        $role_id = $request->role;
        $role = \Spatie\Permission\Models\Role::findById($role_id);
        $group_permission = $request->group_permission;
        $ids_check = $ids_check?$ids_check:[];
        $ids_uncheck = $ids_uncheck?$ids_uncheck:[];
        $arr_check = [];
        $arr_uncheck = [];
        $permission_type_id = RoleHasPermissionType::where(['role_id'=>$role_id])->value('permission_type_id');
        if (!$permission_type_id){
            json_message('Vai trò này chưa được phân nhóm quyền','error');
        }
        foreach ($group_permission as $item){
            $e = (object)($item);

            if (\Str::contains($e->name,'group-permission')){
                if ($e->value!=0) {
                    $pattern = "/\[(.*?)\]/";
                    preg_match_all($pattern, $e->name, $matches);
                    $permission_id = $matches[1][0];
//                    $permission_type_id = $e->value;
                    RolePermissionType::query()
                        ->where('role_id','=',$role_id)
                        ->where('permission_id','=',$permission_id)
                        ->delete();

                    $rolePermissionType = new RolePermissionType();//::firstOrCreate(['role_id'=>$role_id,'permission_id'=>$matches[1][0]]);
                    $rolePermissionType->role_id = $role_id;
                    $rolePermissionType->permission_id = $permission_id;
                    $rolePermissionType->permission_type_id = $permission_type_id;
                    $rolePermissionType->save();
                    $arr_check[] = $permission_id;

                    $role->users->each(function ($user) use($permission_id,$permission_type_id){
                        \DB::table('el_user_permission_type')->updateOrInsert(['user_id'=>$user->id,'permission_id'=>$permission_id],['permission_type_id'=>$permission_type_id]);
                    });
                }else{
                    $pattern = "/\[(.*?)\]/";
                    preg_match_all($pattern, $e->name, $matches);
                    $permission_id = $matches[1][0];
                    RolePermissionType::query()
                        ->where('role_id','=',$role_id)
                        ->where('permission_id','=',$permission_id)
                        ->delete();
                    $role->users->each(function ($user) use($permission_id){
                        UserPermissionType::where(['user_id'=>$user->id,'permission_id'=>$permission_id])->delete();
                    });
                    $arr_uncheck[] = $permission_id;
                }
            }
        }
        $ids_check = array_merge($ids_check,$arr_check);
        $ids_uncheck = array_merge($ids_uncheck,$arr_uncheck);

        if($ids_check) {
            $permission = \Spatie\Permission\Models\Permission::query()
                ->whereIn('id',$ids_check)
                ->select('name')
                ->pluck('name')
                ->toArray();
            $role->givePermissionTo($permission);
//            $permissionsRole = $role->permissions()->get();
//            $role->users->each(function($user) use ($permissionsRole) {
////                $user->syncPermissions($permissionsRole);
//                $user->givePermissionTo($permissionsRole);
//            });
        }


        if ($ids_uncheck){
            $permission = \Spatie\Permission\Models\Permission::query()
                ->whereIn('id',$ids_uncheck)
                ->select('name')
                ->pluck('name')
                ->toArray();

            $role->revokePermissionTo($permission);
        }
        $permissionsRole = $role->permissions()->get();
        $permis =[]; $hasPermis = false;
        $role->users->each(function($user) use ($permissionsRole, &$permis, &$hasPermis) {
//            $permis = [];
            foreach ($permissionsRole as $index => $item) {
                $permis[] = ['permission_id'=>(int)$item->pivot->permission_id,'model_type'=>User::class,'model_id'=>$user->id];
                $hasPermis =true;
            }
//            $user->syncPermissions($permissionsRole);
            \DB::table('el_model_has_permissions')->where(['model_id'=>$user->id])->delete();
        });
        if ($hasPermis){
            foreach (array_chunk($permis,500) as $data_chunk) {
                \DB::table('el_model_has_permissions')->insert($data_chunk);
            }
        }

        json_message(trans('lageneral.successful_save'));
    }

    public function userAssign($role_id){
        $role = Role::findOrFail($role_id);
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        return view('role::user.assign', [
           'role' => $role,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
        ]);
    }

    public function userUnassign($role_id){
        $role = Role::find($role_id);
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        return view('role::user.unassign', [
            'role' => $role,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
        ]);
    }

    public function getUserAssignRole($role, Request $request)
    {
        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $search = $request->input('search','');
        $title = $request->input('title');
        $unit = $request->unit;

        $query = Profile::query();
        $query->select([
            'a.user_id',
            'a.code',
            'a.email',
            'a.lastname',
            'a.firstname',
            'b.name as unit',
            'c.name as title',
        ]);
        $query->from('el_profile as a')
            ->join('el_model_has_roles as ur','ur.model_id','=','a.user_id')
            ->leftjoin('el_unit as b','b.code','=','a.unit_code')
            ->leftjoin('el_titles as c','c.code','=','a.title_code')
            ->where('a.user_id', '>', 2)
            ->where('ur.role_id',$role);

        if ($search){
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $subquery->orWhere('a.lastname', 'like', '%'. $search .'%');
                $subquery->orWhere('a.firstname', 'like', '%'. $search .'%');
                $subquery->orWhere('a.code', 'like', '%'. $search .'%');
            });
        }
        if ($title) {
            $query->where('c.id', '=', $title);
        }
        if ($request->area) {
            $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->WhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.id', $unit_id);
                $sub_query->orWhere('b.id', '=', $unit->id);
            });
        }


        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->full_name = $row->lastname . ' ' . $row->firstname;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveRoleUser($role_id, Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'role' => 'required',
        ], $request, [
            'ids' => 'Tài khoản người dùng',
            'role' => 'Vai trò'
        ]);

        $ids = $request->input('ids', null);
        $role_id = $request->input('role',null);

        $role = \Spatie\Permission\Models\Role::findById($role_id);
        foreach($ids as $id){
            try {
                $exists = UserRole::where(['user_id'=>$id,'role_id'=>$role_id])->exists();
                if ($exists) // 1 user 1 role
                    continue;
                \DB::beginTransaction();
                $user = User::find($id);
                $permission = $role->permissions;
                $user->assignRole($role);
                $user->syncPermissions($permission);
                UserRole::updateOrCreate(['user_id'=>$id,'role_id'=>$role_id]);
                $rolePermissionType = RolePermissionType::where(['role_id'=>$role_id])->selectRaw($id." as user_id, permission_id , permission_type_id")->get();
                foreach ($rolePermissionType as $item) {
                    UserPermissionType::updateOrCreate([
                        'user_id'=>$id,
                        'permission_id'=>$item->permission_id,
                        'permission_type_id'=>$item->permission_type_id
                    ]);
                }
                \DB::commit();
            } catch (Exception $e) {
                \DB::rollBack();
                json_message($e,'error');
            }

        }
        json_message('Cập nhật thành công');
    }

    public function getUserUnassignRole($role,Request $request)
    {
        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $search = $request->input('search','');
        $title = $request->input('title');
        $unit = $request->unit;

        $query = \DB::query();
        $query->select([
            'a.user_id',
            'a.code',
            'a.email',
            'a.lastname',
            'a.firstname',
            'b.name as unit',
            'c.name as title',
        ]);
        $query->from('el_profile as a')
            ->whereNotExists(function (Builder $builder) use($role){
                $builder->select('model_id')->from('el_model_has_roles')->whereColumn('model_id','=','a.user_id'); //->where('role_id','=',$role);
            })
            ->leftjoin('el_unit as b','b.code','=','a.unit_code')
            ->leftjoin('el_titles as c','c.code','=','a.title_code')
        ->where('a.user_id','>',2)->where(['a.status'=>1]);
        if ($search){
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('a.lastname', 'like', '%'. $search .'%');
                $subquery->orWhere('a.firstname', 'like', '%'. $search .'%');
                $subquery->orWhere('a.code', 'like', '%'. $search .'%');
            });
        }

        if ($title) {
            $query->where('c.id', '=', $title);
        }
        if ($request->area) {
            $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->WhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('b.id', $unit_id);
                $sub_query->orWhere('b.id', '=', $unit->id);
            });
        }

//        $query->view('a');
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->full_name = $row->lastname . ' ' . $row->firstname;
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function deleteRoleUser($role_id, Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Tài khoản người dùng',
        ]);

        $ids = $request->input('ids', null);
        foreach($ids as $id){
            User::find($id)->removeRole($role_id);
            UserRole::where(['user_id'=>$id,'role_id'=>$role_id])->delete();
            $rolePermission = \Spatie\Permission\Models\Role::findById($role_id)->permissions;
            User::find($id)->revokePermissionTo($rolePermission);
            \DB::query()->from('el_user_permission_type as a')->join('el_role_permission_type as b', function ($join){
                $join->on('a.permission_id','=','b.permission_id');
                $join->on('a.permission_type_id','=','b.permission_type_id');
            })->where(['b.role_id'=>$role_id,'a.user_id'=>$id])->delete();
        }
        json_message('Cập nhật thành công');
    }

    public function export()
    {
        return (new PermissionExport())->download('danh_sach_vai_tro_'. date('d_m_Y') .'.xlsx');
    }

    public function titleAssign($role_id){
        $role = Role::find($role_id);
        return view('role::title.assign', [
            'role' => $role,
        ]);
    }

    public function getTitleAssignRole($role, Request $request)
    {
        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $title = $request->input('title');

        $query = Titles::query();
        $query->select([
            'a.id',
            'a.code',
            'a.name',
            'b.name as position'
        ]);
        $query->from('el_titles as a')
            ->join('el_role_title as ur','ur.title_id','=','a.id')
            ->leftjoin('el_position as b','b.id','=','a.position_id')
            ->where('ur.role_id', $role);

        if ($title) {
            $query->where('a.id', '=', $title);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function titleUnassign($role_id){
        $role = Role::find($role_id);

        return view('role::title.unassign', [
            'role' => $role,
        ]);
    }

    public function getTitleUnassignRole($role,Request $request)
    {
        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $title = $request->input('title');

        $query = \DB::query();
        $query->select([
            'a.id',
            'a.code',
            'a.name',
            'b.name as position'
        ]);
        $query->from('el_titles as a')
            ->whereNotExists(function (Builder $builder) use($role){
                $builder->select('title_id')
                    ->from('el_role_title')
                    ->whereColumn('title_id','=','a.id')
                    ->where('role_id','=',$role);
            })
            ->leftjoin('el_position as b', 'b.id', '=', 'a.position_id');

        if ($title) {
            $query->where('a.id', '=', $title);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveRoleTitle($role_id, Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Chức danh',
        ]);

        $ids = $request->input('ids', null);
        foreach($ids as $id){
            try {
                TitleRole::updateOrCreate([
                    'title_id' => $id,
                    'role_id' => $role_id
                ]);
            } catch (Exception $e) {
                json_message($e,'error');
            }

        }
        json_message('Cập nhật thành công');
    }

    public function deleteRoleTitle($role_id, Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Tài khoản người dùng',
        ]);

        $ids = $request->input('ids', null);
        foreach($ids as $id){
            TitleRole::where([
                'title_id' => $id,
                'role_id' => $role_id
            ])->delete();
        }
        json_message('Cập nhật thành công');
    }

    public function saveGroupPermission(Request $request)
    {
        $role_id = $request->role_id;
        $redirect = filter_var($request->redirect, FILTER_VALIDATE_BOOL);
        $group_permission = $request->group_permission;
        RoleHasPermissionType::where('role_id',$role_id)->delete();
        $updated = RoleHasPermissionType::updateOrCreate(['role_id'=>$role_id],['permission_type_id'=>$group_permission]);

        if ($updated){
            RolePermissionType::where('role_id',$role_id)->update(['permission_type_id'=>$group_permission]);
            \DB::table('el_user_permission_type as a')->join('el_user_role as b','a.user_id','b.user_id')->where('b.role_id',$role_id)
                ->update(['a.permission_type_id'=>$group_permission]);
        }
        if ($redirect)
            json_result(['status'=>'success','redirect'=>route('backend.roles.edit',['id'=>$role_id])]);
        else
            json_result(['status'=>'ok']);
    }
}
