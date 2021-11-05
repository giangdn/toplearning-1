<?php

namespace App\Http\Controllers\Backend;

use App\LoginImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginImageController extends Controller
{
    public function index() {
        $login = LoginImage::latest()->first();
        return view('backend.login_image.index',['login' => $login]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'image' => 'required',
        ], $request, LoginImage::getAttributeName());

        $model = LoginImage::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $sizes = config('image.sizes.medium');
        $model->image = upload_image($sizes, $request->image);

        if ($request->id) {
            $model->created_by = $model->created_by;
        } else {
            $model->created_by =\Auth::id();
        }
        $model->updated_by = \Auth::id();

        $save = $model->save();
        if($save)
            json_message('lưu thành công', 'success');
        else
            json_message('Không thể lưu', 'error');
    }

    public function form(Request $request) {
        $model = LoginImage::select(['id','status','image','type'])->where('id', $request->id)->first();
        $path_image = image_file($model->image);
        json_result([
            'model' => $model,
            'image' => $path_image
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = LoginImage::query();
        $query->where('type',1);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->user_created = route('backend.get_user_created_updated',['created' => $row->created_by, 'updated' => 0]);
            $row->user_updated = route('backend.get_user_created_updated',['created' => 0, 'updated' => $row->updated_by]);
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
