<?php

namespace App\Http\Controllers\Backend;

use App\Footer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FooterController extends Controller
{
    public function index() {
        return view('backend.footer.index');
    }

    public function form($id = null) {
        $model = Footer::firstOrNew(['id' => $id]);
        $page_title = $model->id ? 'Tiêu đề '. $model->id : trans('backend.add_new');
        return view('backend.footer.form', [
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

        $query = Footer::query();

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.footer.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        Footer::destroy($ids);
        json_message('Đã xóa thành công');
    }

    public function save(Request $request) {
        $model = Footer::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.footer')
            ]);
        }
        json_message('Không thể lưu', 'error');
    }
}
