<?php

namespace App\Http\Controllers\Backend;

use App\Exports\UnitExport;
use App\Imports\UnitImport;
use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Models\Categories\UnitManager;
use App\Profile;
use App\Scopes\DraftScope;
use App\UnitView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyUnitOfCompletedImportUnit;
use Illuminate\Support\Str;
use App\Notifications;
use App\Imports\UnitImportUpdate;

class UnitController extends Controller
{
    public function index($level) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $notifications = Notifications::where('notifiable_id', '=', \Auth::id())
            ->where('notifiable_type', '=', 'App\User')
            ->whereNull('read_at')
            ->get();
        $errors = session()->get('errors');
        \Session::forget('errors');
        $name = Unit::getLevelName($level);

        $name = Unit::getLevelName($level);
        $type = UnitType::get();
        $max_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };

        return view('backend.category.unit.index', [
            'level' => $level,
            'name' => $name,
            'notifications' => $notifications,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
            'max_area' => $max_area,
            'level_name_area' => $level_name_area,
            'type' => $type,
        ]);
    }

    public function getData($level, Request $request) {
        $search = $request->input('search');
        $user_manager = $request->input('user_code');
        $unit_type = $request->input('unit_type');
        $unit = $request->input('unit');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        Unit::addGlobalScope(new DraftScope());
        $query = Unit::query();
        $query->select([
            'el_unit.*',
            'b.name AS parent_name',
            'c.name AS type_name'
        ]);
        $query->leftJoin('el_unit AS b', 'b.code', '=', 'el_unit.parent_code');
        $query->leftJoin('el_unit_type AS c', 'c.id', '=', 'el_unit.type');
        $query->where('el_unit.level', '=', $level);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_unit.code', 'like', '%'. $search .'%');
                $subquery->orWhere('el_unit.name', 'like', '%'. $search .'%');
            });
        }

        if ($user_manager){
            $query->leftJoin('el_unit_manager AS d', 'd.unit_code', '=', 'el_unit.code');
            $query->where('d.user_code', '=', $user_manager);
        }

        if ($unit_type){
            $query->where('el_unit.type', '=', $unit_type);
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_parent_code = Unit::getParentUnitCode($unit->code);
            $unit_child_code = Unit::getArrayChild($unit->code);
            // dd($unit_child_code);
            $query->where(function ($sub_query) use ($unit_parent_code, $unit_child_code, $unit) {
                $sub_query->orWhereIn('el_unit.code', $unit_parent_code);
                $sub_query->orWhereIn('el_unit.id', $unit_child_code);
                $sub_query->orWhere('el_unit.id', '=', $unit->id);
            });
        }

        $count = $query->count();
        $query->orderBy('el_unit.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $unit_manager = UnitManager::query()
                ->select([
                    \DB::raw('CONCAT(user_code ,\' - \', lastname, \' \', firstname) as fullname')
                ])
                ->from('el_unit_manager as a')
                ->leftJoin('el_profile as b', 'b.code', '=', 'a.user_code')
                ->where('a.unit_code', '=', $row->code)
                ->pluck('fullname')->toArray();

            $row->unit_manager = implode('; ', $unit_manager);
            $row->edit_url = route('backend.category.unit.edit', ['level' => $level, 'id' => $row->id]);
            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($level, Request $request) {
        $max_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };

        $model = Unit::findOrFail($request->id);
        $parent = Unit::where('code', '=', $model->parent_code)->first();
        $unit_managers = UnitManager::getUnitManager($model->code);
        $page_title = $model->name;
        $area_code = Area::find($model->area_id);
        $area = Area::getTreeParentArea(@$area_code->code);

        // dd($unit_managers);
        json_result([
            'model' => $model,
            'parent' => $parent,
            'unit_managers' => $unit_managers,
            'max_area' => $max_area,
            'area' => $area,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_unit,code,'. $request->id,
            'name' => 'required',
            'level' => 'required|integer|min:0',
            'parent_id' => 'nullable|exists:el_unit,id',
            'type' => 'nullable|exists:el_unit_type,id',
            'status' => 'required|in:0,1',
            'email' => 'nullable|email'
        ], $request, Unit::getAttributeName());
        //\DB::beginTransaction();

        if ($request->level > 1){
            if (!$request->parent_id){
                json_message('Chưa chọn đơn vị cha', 'error');
            }
        }
        $parent = Unit::findOrNew($request->parent_id);
        $model = Unit::firstOrNew(['id' => $request->id]);
        $model->parent_code = $parent ? $parent->code : null;
        $model->fill($request->all());
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = \Auth::id();
        try {
            if ($model->save()) {
                $managers = $request->manager;
                UnitManager::where('unit_code', '=', $model->code)->where('type', '=', 2)->delete();
                if ($managers) {
                    foreach ($managers as $manager) {
                        $user = Profile::where('user_id', '=', $manager)->first();

                        $check = UnitManager::where('unit_code', '=', $model->code)
                            ->where('user_code', '=', $user->code)->first();
                        if ($check){
                            continue;
                        }

                        $unit_manager = new UnitManager();
                        $unit_manager->user_code = $user->code;
                        $unit_manager->unit_code = $model->code;
                        $unit_manager->save();
                    }
                }

                if ($request->area_id){
                    $unit_id_child = Unit::getArrayChild($model->code);
                    Unit::whereIn('id', $unit_id_child)
                    ->update([
                       'area_id' => $request->area_id,
                    ]);
                }

                json_result([
                    'status' => 'success',
                    'message' => 'Lưu thành công',
                ]);
                        
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            json_message($e->getMessage(), 'error');
        }

        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        Unit::deleteArray($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function treeFolder(Request $request){
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $corporations = Unit::where('level', '=', 1)->where('status', '=', 1)->get();
        return view('backend.category.unit.tree', [
            'corporations' => $corporations,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getChild(Request $request){
        $unit_id = $request->id;
        $unit = Unit::find($unit_id);

        $childs = Unit::where('parent_code', '=', $unit->code)->get(['id', 'name', 'code']);

        $count_child = [];
        foreach ($childs as $item){
            $count_child[$item->id] = Unit::countChild($item->code);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child];
        return \response()->json($data);
    }

    public function export($level)
    {
        return (new UnitExport($level))->download('danh_sach_don_vi_'. date('d_m_Y') .'.xlsx');
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit_id = $request->unit_id;
        $file = $request->file('import_file');
        $name = 'import_unit_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new UnitImport(\Auth::user()))->queue($newfile)->chain([
                new NotifyUnitOfCompletedImportUnit(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('backend.category.unit',['level' => $unit_id])
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể tải lên file',
            'redirect' => route('backend.category.unit', ['level' => 1]),
        ]);
    }

    public function importUpdate(Request $request){
        $this->validateRequest([
            'import_file_update' => 'required|file'
        ], $request, ['import_file_update' => 'File import']);

        $unit_id = $request->unit_id;
        $file = $request->file('import_file_update');
        $name = 'import_unit_update_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new UnitImportUpdate(\Auth::user()))->queue($newfile)->chain([
                new NotifyUnitOfCompletedImportUnit(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('backend.category.unit',['level' => $unit_id])
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể tải lên file',
            'redirect' => route('backend.category.unit', ['level' => 1]),
        ]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Cấp bậc',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Unit::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Unit::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
