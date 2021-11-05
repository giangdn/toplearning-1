<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications;
use App\Imports\AreaImport;
use App\Jobs\NotifyAreaOfCompletedImportUnit;
use Illuminate\Support\Str;

class AreaController extends Controller
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
        $name = Area::getLevelName($level);
        $parent_area = Area::getAreaParent($level);
        $units3 = Unit::where('level',3)->where('status',1)->get();
        return view('backend.category.area.index', [
            'level' => $level,
            'name' => $name,
            'parent_area' => $parent_area,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
            'units3' => $units3
        ]);
    }

    public function getData($level, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'a.id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Area::query();
        $query->select([
            'a.*',
            'b.name AS parent_name'
        ]);
        $query->from('el_area AS a');
        $query->leftJoin('el_area AS b', 'b.code', '=', 'a.parent_code');
        $query->where('a.level', '=', $level);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('a.code', 'like', '%'. $search .'%');
                $subquery->orWhere('a.name', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.area.edit', ['level' => $level, 'id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($level, Request $request) {
        $model = Area::select(['id','status','code','name','parent_code','unit_id'])->where('id', $request->id)->first();
        $parent = Area::select('id')->where('code', '=', $model->parent_code)->first();
        json_result([
            'model' => $model,
            'parent' => $parent
        ]);

        $model = new Area();
        $page_title = trans('backend.add_new');

        return view('backend.category.area.form', [
            'model' => $model,
            'level' => $level,
            'page_title' => $page_title,
            'name' => $name,
            'parent_area' => $parent_area,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_area,code,'. $request->id,
            'name' => 'required',
            'level' => 'required|integer|min:0|exists:el_area,level',
            'parent_id' => 'nullable|exists:el_area,id',
            'status' => 'required|in:0,1',
        ], $request, Area::getAttributeName());

        $parent = Area::findOrNew($request->parent_id);
        $model = Area::firstOrNew(['id' => $request->id]);
        $model->parent_code = $parent->code;
        $model->fill($request->all());

        if ($model->save()) {
            if ($model->save()) {
                json_result([
                    'status' => 'success',
                    'message' => 'Lưu thành công',
                ]);                
            }
        }
        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        Area::deleteArray($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $area_id = $request->area_id;
        $file = $request->file('import_file');
        $name = 'import_area_' . Str::random(10) . '.' . $file->extension();
        $newfile = $file->move(storage_path('import_files'), $name);

        if($newfile) {
            (new AreaImport(\Auth::user()))->queue($newfile)->chain([
                new NotifyAreaOfCompletedImportUnit(\Auth::user()),
            ]);

            json_result([
                'status' => 'success',
                'message' => 'Đang import dữ liệu, bạn sẽ được thông báo khi hoàn thành...',
                'redirect' => route('backend.category.area',['level' => $area_id])
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể tải lên file',
            'redirect' => route('backend.category.area', ['level' => 1]),
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
                $model = Area::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Area::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
