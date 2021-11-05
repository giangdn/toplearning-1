<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\TrainingType;

class TrainingTypeController extends Controller
{
     public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        \Session::forget('errors');
        return view('backend.category.training_type.index',[
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
        $query = TrainingType::query();
        $query->select([
            '*'
        ]);

        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhere('code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.training-type.edit', ['id' => $row->id]);
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function form(Request $request) {
        $model = TrainingType::select(['id','code','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_position,code,'. $request->id,
            'name' => 'required',
            // 'status' => 'required|in:0,1',
        ], $request, TrainingType::getAttributeName());

        $model = TrainingType::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

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
        
        TrainingType::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

}
