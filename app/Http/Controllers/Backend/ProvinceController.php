<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\District;
use App\Imports\ProviceImport;
use App\Models\Categories\Province;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Models\Categories\UnitManager;
use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use User\Acl\Rule;

class ProvinceController extends Controller
{
    public function index( ) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        return view('backend.category.province.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData( Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        Province::addGlobalScope(new DraftScope());
        $query = Province::query();
        $query->select(['*']);
        $query->from('el_province');

        if ($search) {
            $query->orWhere('id', 'like', '%'. $search .'%');
            $query->orWhere('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = Province::select(['id','code','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|integer|min:1|max:500',
            'name' => 'required|max:250',
        ], $request, Province::getAttributeName());

        $model = Province::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = \Auth::id();
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

        $related = District::whereIn('province_id', $ids)->first();
        $related1 = TrainingLocation::whereIn('province_id', $ids)->first();
        if ($related || $related1){
            json_message('Có dữ liệu liên quan. Không thể xoá', 'error');
        }

        Province::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ProviceImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('backend.category.province'),
        ]);
    }
}
