<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\District;
use App\Models\Categories\Province;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingLocation;

class TrainingLocationController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $province= Province::all();
        $district = District::get();
        return view('backend.category.training_location.index',[
            'province' =>$province,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
            'district'=>$district
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        TrainingLocation::addGlobalScope(new DraftScope());
        $query = TrainingLocation::query()
            ->leftJoin('el_province as b','el_training_location.province_id','=','b.id')
            ->leftJoin('el_district as c','el_training_location.district_id','=','c.id')
            ->select(['el_training_location.*','b.name as province','c.name as district']);
        if ($search) {
            $query->orWhere('el_training_location.code', 'like', '%'. $search .'%');
            $query->orWhere('el_training_location.name', 'like', '%'. $search .'%');
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
        $model = TrainingLocation::select(['id','status','code','name','district_id','province_id'])->where('id', $request->id)->first();
        $province = Province::select(['id','name'])->where('id', $model->province_id)->first();
        $districts = District::select(['id','name'])->where('province_id', $province->id)->get();
        json_result([
            'model' => $model,
            'province' => $province,
            'districts' => $districts,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_training_location,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1'
        ], $request, TrainingLocation::getAttributeName());
        $validator = \Validator::make($request->all(),
            [
            'province_id' => 'required',
            'district_id' => 'required'
            ],
            [
                'province_id.required'=>'Chưa chọn tỉnh thành',
                'district_id.required'=>'Chưa chọn quận huyện'
            ]
        );
        if($validator->fails()){
            json_message($validator->errors()->all()[0], 'error');
        }
        $model = TrainingLocation::firstOrNew(['id' => $request->id]);
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
        TrainingLocation::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
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
                $model = TrainingLocation::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = TrainingLocation::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
