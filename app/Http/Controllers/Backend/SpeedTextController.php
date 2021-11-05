<?php

namespace App\Http\Controllers\Backend;

use App\SpeedText;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpeedTextController extends Controller
{
    public function index() {
        return view('backend.speed_text.index');
    }

    public function form($id = null) {
        $model = SpeedText::firstOrNew(['id' => $id]);
        $page_title = $model->id ? 'Tiêu đề '. $model->id : trans('backend.add_new');
        return view('backend.speed_text.form', [
            'model' => $model,
            'page_title' => $page_title
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        $search = $request->get('search');

        $query = SpeedText::query();

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('title', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.speed_text.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        SpeedText::destroy($ids);
        json_message('Đã xóa thành công');
    }

    public function save(Request $request) {
        $model = SpeedText::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.speed_text')
            ]);
        }
        json_message('Không thể lưu', 'error');
    }
}
