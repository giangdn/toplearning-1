<?php

namespace App\Http\Controllers\Backend;

use App\WebService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebServiceController extends Controller
{
    public function index() {
        return view('backend.webservice.index');
    }

    public function form($id = null) {
        $model = WebService::firstOrNew(['id' => $id]);
        $page_title = $model->id ? 'Cấu hình '. $model->id : trans('backend.add_new');

        return view('backend.webservice.form', [
            'model' => $model,
            'page_title' => $page_title
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        $search = $request->get('search');

        $query = WebService::query();

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('backend.webservice.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        WebService::destroy($ids);
        json_message('Đã xóa thành công');
    }

    public function save(Request $request) {
        $model = WebService::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.webservice')
            ]);
        }
        json_message('Không thể lưu', 'error');
    }
}
