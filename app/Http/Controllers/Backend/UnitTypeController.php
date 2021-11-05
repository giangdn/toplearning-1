<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\UnitType;
use App\Models\Categories\UnitTypeCode;

class UnitTypeController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('backend.category.unit_type.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        UnitType::addGlobalScope(new DraftScope());
        $query = UnitType::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.unit_type.edit', ['id' => $row->id]);
            $get_units_type_code = UnitTypeCode::where('unit_type_id', $row->id)->get();

            $units_type_code = [];
            foreach($get_units_type_code as $get_unit_type_code) {
                $units_type_code[] = $get_unit_type_code->code;
            }
            $row->unit_type_code = !empty($units_type_code) ? implode(', ',$units_type_code) : '-';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = UnitType::findOrFail($request->id);
        $units_type_code = UnitTypeCode::where('unit_type_id',$request->id)->get();

        $html = '';
        if (!empty($units_type_code)) {
            foreach ($units_type_code as $key => $unit_type_code) {
                $html .= '<span class="unit_type_code">
                            '. $unit_type_code->code .'
                            <span class="delete_code" onclick="deleteUnitCode('. $unit_type_code->id .')">x</span>
                        </span>';
            }
        }
        // dd($html);
        json_result([
            'model' => $model,
            'html' => $html,
        ]);
    }

    public function form1($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        if ($id) {
            $model = UnitType::find($id);
            $page_title = $model->name;
            $units_type_code = UnitTypeCode::where('unit_type_id',$id)->get();
        }
        return view('backend.category.unit_type.form', [
            'model' => $model,
            'page_title' => $page_title,
            'units_type_code' => $units_type_code,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => ['required','regex:/[^~!@#%^&*()_+{}:;,.""|<>?[]$/'],
        ], $request, UnitTypeCode::getAttributeName());

        $check_isset_code = UnitTypeCode::where('code', $request->code)->first();
        if(isset($check_isset_code)) {
            json_result([
                'status' => 'warning',
                'message' => 'Mã đã tồn tại',
                'redirect' => route('backend.category.unit_type.edit', [
                    'id' => $request->id
                ])
            ]);
        }

        $model = UnitTypeCode::firstOrNew(['unit_type_id' => $request->id,'code' => $request->code]);
        $model->code = $request->code;
        $model->unit_type_id = $request->id;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.category.unit_type.edit', [
                    'id' => $request->id
                ])
            ]);
        }
        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $id = $request->input('id', null);
        UnitTypeCode::where('id',$id)->delete();
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
