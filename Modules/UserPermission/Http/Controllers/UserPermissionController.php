<?php

namespace Modules\UserPermission\Http\Controllers;

use App\Models\Categories\Area;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\PermissionType;
use App\Profile;
use App\Role;
use App\RolePermissionType;
use App\Scopes\DraftScope;
use App\User;
use App\UserPermissionType;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $max_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };
        return view('userpermission::backend.index', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'max_area' => $max_area,
            'level_name_area' => $level_name_area
        ]);
    }
    public function getData(Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $prefix = \DB::getTablePrefix();
        Profile::addGlobalScope(new DraftScope('user_id'));
        $isPermission = (int) \Auth::user()->can('permission-user-permission');
        $query = Profile::query();
        $query->select([
            'el_profile.id',
            'el_profile.user_id',
            'el_profile.code',
            'el_profile.firstname',
            'el_profile.lastname',
            'el_profile.email',
            'd.username',
            'b.name AS unit_name',
            'c.name AS title_name',
            'e.name AS unit_manager',
            \DB::raw("$isPermission as is_permission")
        ]);
        $query->leftJoin('el_unit AS b', 'b.code', '=', 'el_profile.unit_code');
        $query->leftJoin('el_unit AS e', 'e.code', '=', 'b.unit_code');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'el_profile.title_code');
        $query->leftJoin('user as d','d.id','=','el_profile.user_id');
        $query->where('el_profile.user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search,$prefix) {
                $sub_query->orWhere(\DB::raw('CONCAT('.$prefix.'el_profile.lastname, \' \', '.$prefix.'el_profile.firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile.code', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('el_profile.status', '=', $status);
        }
        if ($request->area) {
            $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
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

        if ($title) {
            $title = Titles::where('id', '=', $title)->first();
            $query->where('el_profile.title_code', '=', $title->code);
        }


        $count = $query->count();
        $query->orderBy('el_profile.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.backend.user.edit', ['id' => $row->user_id]);
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);
            $row->permission_url = route('module.userpermission.form', ['user_id' => $row->user_id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function getPermission($user_id,Request $request) {
        $sort = $request->input('sort', 'id');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $search = $request->input('search','');
        $query = \DB::query()->select([
            'a.*',
            'b.*',
            'c.permission_type_id'
        ])
            ->from('el_permissions as a')
            ->leftJoin('el_model_has_permissions as b', function($join) use ($user_id){
                $join->on('a.id','=','b.permission_id')
                    ->where('b.model_id','=',$user_id);
            })
            ->leftJoin('el_user_permission_type as c',function ($join) use($user_id){
                $join->on('c.permission_id','=','a.id')
                    ->where('c.user_id','=',$user_id);
            });
        $query->whereNotNull('a.model');
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
        foreach ($rows as $row){
            $row->permission_type = $permission_type;
            $row->permission = \DB::query()->select(['a.id','a.name','a.description','b.permission_id'])
                ->from('el_permissions as a')
                ->leftJoin('el_model_has_permissions as b', function ($join) use($user_id){
                    $join->on( 'a.id','=','b.permission_id')
                        ->where('b.model_id','=',$user_id);
                })
                ->where('a.parent','=',$row->name) ->get();
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('userpermission::create');
    }
    public function store($user_id, Request $request)
    {
        $ids_check = $request->ids;
        $ids_uncheck = $request->ids_uncheck;
        $role_id = $request->role;
        $user = User::find($user_id);
        $group_permission = $request->group_permission;
        $ids_check = $ids_check?$ids_check:[];
        $ids_uncheck = $ids_uncheck?$ids_uncheck:[];
        $arr_check = [];
        $arr_uncheck = [];
        foreach ($group_permission as $item){
            $e = (object)($item);

            if (\Str::contains($e->name,'group-permission')){
                if ($e->value!=0) {
                    $pattern = "/\[(.*?)\]/";
                    preg_match_all($pattern, $e->name, $matches);
                    UserPermissionType::where('user_id','=',$user_id)->where('permission_id','=',$matches[1][0])->delete();
                    UserPermissionType::firstOrCreate(['user_id'=>$user_id,'permission_id'=>$matches[1][0],'permission_type_id'=>$e->value]);
                    $arr_check[] = $matches[1][0];
                }else{
                    $pattern = "/\[(.*?)\]/";
                    preg_match_all($pattern, $e->name, $matches);

                    $arr_uncheck[] = $matches[1][0];
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

            $user->givePermissionTo($permission);
            /*$permissionsRole = $role->permissions()->get();
            $role->users->each(function($user) use ($permissionsRole) {
                $user->givePermissionTo($permissionsRole);
            });*/
        }


        if ($ids_uncheck){
            $permission = \Spatie\Permission\Models\Permission::query()
                ->whereIn('id',$ids_uncheck)
                ->select('name')
                ->pluck('name')
                ->toArray();
            $user->revokePermissionTo($permission);
           /* $role->revokePermissionTo($permission);
            $permissionsRole = $role->permissions()->get();
            $role->users->each(function($user) use ($permissionsRole,$permission) {
                $user->syncPermissions($permissionsRole);

            });*/

        }
        json_message(trans('lageneral.successful_save'));
    }

    public function form($user_id)
    {
        return view('userpermission::backend.permission', [
            'user_id' => $user_id
        ]);
    }

}
