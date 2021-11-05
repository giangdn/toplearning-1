<?php

namespace Modules\PermissionType\Http\Controllers;

use App\PermissionType;
use App\PermissionTypeUnit;
use App\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class PermissionTypeController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        return view('permissiontype::backend.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }
    public function getData( Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        PermissionType::addGlobalScope(new DraftScope());
        $query = PermissionType::query()->select(['*']);
        $query->where('type', '!=', 1);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row){
            if ($row->type == 1){
                $row->created_by = trans('backend.default');
                $row->updated_by = trans('backend.default');
            }else{
                $created_by = Profile::find($row->created_by);
                $updated_by = Profile::find($row->updated_by);

                $row->created_by = ($created_by->lastname . ' ' . $created_by->firstname);
                $row->updated_by = ($updated_by->lastname . ' ' . $updated_by->firstname);
            }

            $row->permission_edit = userCan('permission-group-edit');
            $row->permission_delete = userCan('permission-group-delete');

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function showModal(Request $request) {
        $model = PermissionType::firstOrNew(['id' => $request->id]);
        $permission_type=$request->input('id',0);
        $units = \DB::query()->select(['a.id','a.code','a.name','a.level','a.parent_code','b.unit_id','b.type'])
            ->from('el_unit as a')
            ->leftJoin('el_permission_type_unit as b', function ($join) use ($permission_type){
            $join->on('a.id','=','b.unit_id')->where('permission_type_id','=',$permission_type);
        })->get();
        return view('permissiontype::modal.add_permission_type', [
            'model' => $model,
            'units' => $units
        ]);
    }
    public function save(Request $request) {
        $this->validateRequest([
            'id' => 'nullable|exists:el_permission_type,id',
            'name' => 'required|string|unique:el_permission_type,name,'.$request->id
        ], $request, PermissionType::getAttributeName());

        $validator = \Validator::make($request->all(),
            ['unit'=>'required'],['unit.*'=>'Chưa chọn đơn vị']);
        if($validator->fails()){
            json_message($validator->errors()->all()[0], 'error');
        }

        $max_sort = PermissionType::orderByDesc('sort')->first();

        $model = PermissionType::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if (empty($request->id)) {
            $model->created_by = \Auth::id();
        }
        $model->updated_by = \Auth::id();
        $model->type = 2;
        $model->sort = $request->id ? $model->sort : ($max_sort->sort + 1);
        $units = $request->unit;
        $type = $request->type;
        if ($model->save()) {
            foreach ($units as $value){
                $data[] = ['permission_type_id'=>$model->id,'unit_id'=>$value,'type'=>$type[$value]];
            }
            PermissionTypeUnit::query()->where('permission_type_id','=',$model->id)->delete();
            PermissionTypeUnit::query()->insert($data);

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.permission.type')
            ]);
        }

        json_message('Không thể lưu dữ liệu', 'error');
    }
    public function delete(Request $request) {
        PermissionType::query()->where('type','=',2)->where('id','=',$request->ids)->delete();
        json_message('Đã xóa thành công');
    }
}
