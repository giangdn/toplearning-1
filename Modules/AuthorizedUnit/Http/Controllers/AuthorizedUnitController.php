<?php

namespace Modules\AuthorizedUnit\Http\Controllers;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories\Area;

class AuthorizedUnitController extends Controller
{
    public function index()
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        return view('authorizedunit::backend.authorizedunit.index', [
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }
    public function getData(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $unit_manager = Permission::isUnitManager();

        $query = Profile::query();
        $query->select([
            'el_profile.id',
            'el_profile.user_id',
            'el_profile.code',
            'el_profile.email',
            'el_profile.firstname',
            'el_profile.lastname',
            'el_profile.status',
            'b.name AS unit_name',
            'c.name AS title_name',
            'd.name AS unit_parent_name',
        ]);
        $query->from('el_profile');
        $query->leftJoin('el_unit AS b', 'b.code', '=', 'el_profile.unit_code');
        $query->leftJoin('el_area AS area', 'area.id', '=', 'b.area_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.parent_code');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'el_profile.title_code');
        $query->where('el_profile.user_id', '>', 2);
        $query->whereIn('el_profile.code', function ($sub){
            $sub->select(['user_code'])
                ->from('el_unit_manager')
                ->where('manager_type', '=', 2)
                ->pluck('user_code')
                ->toArray();
        });

        if ($unit_manager){
            $profile = Profile::find(Auth::id());
            $user_manager = UnitManager::where('user_code', '=', $profile->code)->where('manager_type', '=', 1)->get();

            foreach ($user_manager as $manager){
                $unit_arr = Unit::getArrayChild($manager->unit_code);
                $query->orWhereIn('b.id', $unit_arr);
            }
        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile.code', 'like', '%'. $search .'%');
            });
        }

        if ($request->area) {
            $area = Area::find($request->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->WhereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }

        if (!is_null($status)) {
            $query->where('el_profile.status', '=', $status);
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
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);

            $unit_manager = UnitManager::whereUserCode($row->code)
                ->leftJoin('el_unit as unit', 'unit.code', '=', 'el_unit_manager.unit_code')
                ->pluck('unit.name')
                ->toArray();

            $row->unit_manager = implode('; ', $unit_manager);
        }

        json_result(['total' => $count, 'rows' => $rows]);

    }
    public function form() {
        $page_title = trans('backend.add_new');
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        return view('authorizedunit::backend.authorizedunit.form', [
            'page_title' => $page_title,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
        ]);
    }

    public function getDataNoManager(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $unit = $request->unit;
        $title = $request->input('title');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $unit_manager = Permission::isUnitManager();

        $query = Profile::query();
        $query->select([
            'el_profile.id',
            'el_profile.user_id',
            'el_profile.code',
            'el_profile.email',
            'el_profile.firstname',
            'el_profile.lastname',
            'el_profile.status',
            'b.name AS unit_name',
            'c.name AS title_name',
            'd.name AS unit_manager',
        ]);
        $query->from('el_profile');
        $query->leftJoin('el_unit AS b', 'b.code', '=', 'el_profile.unit_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.parent_code');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'el_profile.title_code');
        $query->where('el_profile.user_id', '>', 2);
        $query->whereNotIn('el_profile.code', function ($sub){
            $sub->select(['user_code'])
                ->from('el_unit_manager')
                ->where('manager_type', '=', 2)
                ->pluck('user_code')
                ->toArray();
        });

        if ($unit_manager){
            $unit_user = Unit::find(session('user_unit'));
            $child_arr = Unit::getArrayChild(@$unit_user->code);

            $query->where('el_profile.user_id', '!=', Auth::id());
            $query->where(function ($sub) use ($unit_user, $child_arr){
                $sub->orWhere('b.id', '=', @$unit_user->id);
                $sub->orWhereIn('b.id', $child_arr);
            });
        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile.code', 'like', '%'. $search .'%');
            });
        }

        if (!is_null($status)) {
            $query->where('el_profile.status', '=', $status);
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
            $row->unit_url = route('module.backend.user.get_unit', ['user_id' => $row->user_id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);

    }

    public function save(Request $request) {
        $this->validateRequest ([
            'ids' => 'required',
        ], $request);

        $profile = Profile::find(Auth::id());
        $unit_manager = UnitManager::where('user_code', '=', $profile->code)->where('manager_type', '=', 1)->get();

        $profile_manager = Profile::whereIn('user_id', $request->ids)->pluck('code')->toArray();
        foreach ($profile_manager as $manger_code){
            foreach ($unit_manager as $unit){
                $model = new UnitManager();
                $model->user_code = $manger_code;
                $model->unit_code = $unit->unit_code;
                $model->manager_type = 2;
                $model->type = 2;
                $model->save();
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        $unit_manager = UnitManager::leftJoin('el_profile as b', 'b.code', '=', 'el_unit_manager.user_code')
            ->where('el_unit_manager.manager_type', '=', 2)
            ->whereIn('b.user_id', $ids)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
