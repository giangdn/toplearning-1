<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CostLessons;

class CostLessonsController extends Controller
{
    public function index() {
        return view('backend.category.cost_lessons.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CostLessons::query();
        
        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%'. $search .'%');
                $sub_query->orWhere('cost', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.cost_lessons.edit', ['id' => $row->id]);
            $row->cost = number_format($row->cost, 0, ',', '.');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        if ($id) {
            $model = CostLessons::find($id);
            $page_title = $model->name;
        }
        else {
            $model = new CostLessons();
            $page_title = trans('backend.add_new');
        }
        return view('backend.category.cost_lessons.form', [
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'cost' => 'required|string',
        ], $request, CostLessons::getAttributeName());
        
        $model = CostLessons::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->cost = unnumber_format($model->cost);

        if ($model->save()) {
            if ($request->id){
                json_result([
                    'status' => 'success',
                    'message' => 'Lưu thành công',
                    'redirect' => route('backend.category.cost_lessons.edit', [
                        'id' => $model->id
                    ])
                ]);
            }else{
                json_result([
                    'status' => 'success',
                    'message' => 'Lưu thành công',
                    'redirect' => route('backend.category.cost_lessons.create')
                ]);
            }
        }

        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        CostLessons::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
