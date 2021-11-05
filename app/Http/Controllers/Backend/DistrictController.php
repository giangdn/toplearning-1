<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\District;
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

class DistrictController extends Controller
{
    public function index( ) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $province = Province::all();
        return view('backend.category.district.index',[
            'province' => $province,
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
        District::addGlobalScope(new DraftScope());
        $query = District::query();
        $query->select(['el_district.*','b.name as province']);
        $query->join('el_province as b','el_district.province_id','=','b.id');

        if ($search) {
            $query->orWhere('el_district.id', 'like', '%'. $search .'%');
            $query->orWhere('el_district.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('el_district.'.$sort, $order);
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
        $model = District::select(['id','province_id','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'id' => 'required|integer|min:1|max:999999',
            'name' => 'required|max:250',
        ], $request, District::getAttributeName());
        $validator = \Validator::make($request->all(),
            ['province_id'=> 'required|integer|min:1',],
            ['province_id.required'=> 'Chưa chọn thành phố']);
        if($validator->fails()){
            json_message($validator->errors()->all()[0], 'error');
        }
        $model = District::firstOrNew(['id' => $request->id]);
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

    public function filter(Request $request)
    {
        $district = District::query()->where('province_id','=',$request->province_id)->get();
        json_result($district);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        $related = TrainingLocation::whereIn('district_id', $ids)->first();
        if ($related){
            json_message('Có dữ liệu liên quan. Không thể xoá', 'error');
        }

        District::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
