<?php

namespace App\Http\Controllers\Backend;

use App\AdvertisingPhoto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scopes\DraftScope;

class AdvertisingPhotoController extends Controller
{
    public function index($type) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        if($type == 0 ) {
            $page_title = 'Ảnh quảng cáo tin tức chung';
        } else {
            $page_title = 'Ảnh quảng cáo tin tức';
        }

        // return view('backend.advertising_photo.index',[
        return view('backend.advertising_photo.index2',[
            'page_title' => $page_title,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
            'type' => $type
        ]);
    }

    public function form(Request $request) {
        $model = AdvertisingPhoto::select(['id','status','image','url'])->where('id', $request->id)->first();
        $path_image = image_file($model->image);
        json_result([
            'model' => $model,
            'image' => $path_image
        ]);
    }

    public function getData($type, Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        AdvertisingPhoto::addGlobalScope(new DraftScope());
        $query = AdvertisingPhoto::query();
        $query->where('type', $type);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('backend.advertising_photo.edit', ['type' => $type, 'id' => $row->id]);
            $row->image_url = image_file($row->image);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        AdvertisingPhoto::destroy($ids);
        json_message('Đã xóa thành công');
    }

    public function save(Request $request) {
        $this->validateRequest([
            'image' => 'required_if:id,',
            'status' => 'required',
            'type' => 'required',
        ], $request, [
            'image' => 'Hình ảnh',
            'status' => 'Trạng thái',
            'type' => 'loại',
        ]);

        $model = AdvertisingPhoto::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        
        if ($request->image) {
            $sizes = config('image.sizes.library');
            $model->image = upload_image($sizes, $request->image);
        }

        if (empty($model->id)) {
            $model->created_by = \Auth::id();
        }
        $model->updated_by = \Auth::id();

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.advertising_photo', ['type' => $request->type])
            ]);
        }

        json_message('Không thể lưu', 'error');
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
                $model = AdvertisingPhoto::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = AdvertisingPhoto::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
