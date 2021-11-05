<?php

namespace App\Http\Controllers\Backend;

use App\Imports\TitlesImport;
use App\Exports\TitlesExport;
use App\Models\Categories\Position;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use App\Models\Categories\TitleRank;
use Modules\Capabilities\Entities\CapabilitiesTitle;
use App\Profile;
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use App\Models\Categories\UnitType;

class TitlesController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $title_ranks = TitleRank::where('status',1)->get();
        $units_type = UnitType::get();
        \Session::forget('errors');
        return view('backend.category.titles.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
            'title_ranks' => $title_ranks,
            'units_type' => $units_type,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $group = $request->input('group');
        $unit = $request->input('unit');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        Titles::addGlobalScope(new DraftScope());
        $query = Titles::query();
        $query->select([
            'el_titles.id',
            'el_titles.code',
            'el_titles.name',
            'el_titles.status',
            'el_titles.created_by',
            'el_titles.updated_by',
            'unit.name AS unit_name',
            'unit.level AS unit_level',
            'unit.code AS unit_code',
            'tr.name as title_rank_name',
            'ut.name as unit_type_name',
        ]);
        $query->leftJoin('el_unit AS unit', 'unit.id', '=', 'el_titles.unit_id');
        $query->leftJoin('el_title_rank AS tr', 'tr.id', '=', 'el_titles.group');
        $query->leftJoin('el_unit_type AS ut', 'ut.id', '=', 'el_titles.unit_type');

        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('el_titles.name', 'like', '%'. $search .'%');
                $subquery->orWhere('el_titles.code', 'like', '%'. $search .'%');
            });
        }

        if ($group) {
            $query->where('group', '=', $group);
        }

        if ($unit) {
            $unit = explode(';', $unit);
            $query->whereIn('unit_id', $unit);
        }

        $count = $query->count();
        $query->orderBy('el_titles.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.titles.edit', ['id' => $row->id]);
            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function form(Request $request) {
        $model = Titles::findOrFail($request->id);
        $unit_code = @Unit::find($model->unit_id)->code;
        $unit = Unit::getTreeParentUnit($unit_code);
        $position = Position::find($model->position_id);
        // dd($unit);
        json_result([
            'model' => $model,
            'unit' => $unit,
            'position' => $position,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_titles,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1',
            'group' => 'nullable',
            'unit_id' => 'nullable|exists:el_unit,id'
        ], $request, Titles::getAttributeName());
        $model = Titles::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->position_id = $request->position_id;
        $model->unit_type = $request->unit_type;
        $model->created_by = $model->created_by ? $model->created_by : \Auth::id();
        if ($model->unit_id) {
            $model->unit_level = Unit::find($model->unit_id)->level;
        }

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
            ]);
        }

        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach($ids as $id) {
            $checkTitleCareerRoadmap = CareerRoadmap::where('title_id','=',$id)->get();
            $checkTitleProfile = Profile::where('title_id','=',$id)->get();
            if ( !$checkTitleProfile->isEmpty() || !$checkTitleCareerRoadmap->isEmpty()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Không thể xóa vì có liên quan đến người dùng hoặc lộ trình',
                ]);
            } else {
                Titles::find($id)->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new TitlesImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('backend.category.titles'),
        ]);
    }

    public function export()
    {
        return (new TitlesExport())->download('danh_sach_chuc_danh_'. date('d_m_Y') .'.xlsx');
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Chức danh',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Titles::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Titles::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
