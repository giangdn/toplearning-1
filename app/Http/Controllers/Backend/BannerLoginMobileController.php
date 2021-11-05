<?php

namespace App\Http\Controllers\Backend;

use App\LoginImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerLoginMobileController extends Controller
{
    public function index() {
        return view('backend.banner_mobile.index');
    }

    public function save(Request $request) {
        $this->validateRequest([
            'image' => 'required',
        ], $request, LoginImage::getAttributeName());

        $model = LoginImage::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->image = path_upload($request->image);
        $model->status = $request->status;
        $model->type = 2;
        $model->created_by = \Auth::id();
        $model->updated_by = \Auth::id();

        $save = $model->save();
        if($save)
            json_message('lưu thành công', 'success');
        else
            json_message('Không thể lưu', 'error');
    }

    public function form($id = null) {
        $model = LoginImage::firstOrNew(['id' => $id]);

        $page_title = $id ? 'Banner '. $id : trans('backend.add_new');
        return view('backend.banner_mobile.form', [
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = LoginImage::query();
        $query->where('type',2);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('backend.banner_login_mobile.edit', ['id' => $row->id]);
            $row->image_url = image_file($row->image);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        LoginImage::destroy($ids);
        json_message('Đã xóa thành công');
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
                $model = LoginImage::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = LoginImage::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
