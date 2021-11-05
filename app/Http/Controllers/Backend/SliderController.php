<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use App\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic;

class SliderController extends Controller
{
    public function index() {
        return view('backend.slider.index');
    }

    public function form($id = null) {
        $model_web = Slider::where('type', '=', 1)->firstOrNew(['id' => $id]);
        $model_app = Slider::where('type', '=', 2)->firstOrNew(['id' => $id]);
        $unit = Unit::where('level', '=', 1)->get();
        $page_title = $id ? 'Banner '. $id : trans('backend.add_new');
        !empty($model_web->object) && $id ? $get_slider_web = json_decode($model_web->object) : $get_slider_web = [];
        return view('backend.slider.form', [
            'model_web' => $model_web,
            'model_app' => $model_app,
            'page_title' => $page_title,
            'unit' => $unit,
            'get_slider_web' => $get_slider_web,
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        Slider::addGlobalScope(new DraftScope());
        $query = Slider::query();
        $query->select([
            'el_slider.*',
            'b.name as unit_name',
        ])
            ->leftJoin('el_unit as b', 'b.id', '=', 'el_slider.location');

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->location = $row->unit_name;
            $row->edit_url = route('backend.slider.edit', ['id' => $row->id]);
            $row->image_url = image_file($row->image);
            $row->type = ($row->type == 1 ? 'Web' : 'Mobile');

            $units = Unit::where('level', '=', 1)->get();
            $objects = [];
            if (!empty($row->object)) {
                $get_objects = json_decode($row->object);
                foreach($units as $unit) {
                    in_array($unit->id, $get_objects) && $objects[] = $unit->name;
                }
            }
            $row->objects = $objects;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        Slider::destroy($ids);
        json_message('Đã xóa thành công');
    }

    public function save(Request $request) {
        $this->validateRequest([
            'image' => 'required_if:id,',
            'display_order' => 'required'
        ], $request, [
            'image' => 'Hình ảnh',
            'display_order' => 'Thứ tự',
            'status' => 'Trạng thái'
        ]);
        if ($request->location == null) {
            $request->location = 0;
        }
        $model = Slider::firstOrNew(['id' => $request->id, 'type' => $request->type]);
        $model->fill($request->all());

        $sizes = config('image.sizes.slide');
        $model->image = upload_image($sizes, $request->image);

        $model->location = $request->location;
        $model->object = !empty($request->object) && is_array($request->object) ? json_encode($request->object) : '';
        if (empty($model->id)) $model->created_by = \Auth::id();
        $model->updated_by = \Auth::id();

        if ($model->save()) {

            /*$uploadPath = \Config('app.datafile.dataroot').'/uploads/'.$model->image;

            $resize_image = ImageManagerStatic::make($uploadPath);
            if ($model->type == 1){
                $resize_image->resize(1500, 200);
            }else{
                $resize_image->resize(500, 200);
            }
            $resize_image->save($uploadPath);*/

            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.slider')
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
                $model = Slider::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Slider::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
