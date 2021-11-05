<?php

namespace App\Http\Controllers\Backend;

use App\Config;
use App\LogoModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;

class LogoController extends Controller
{
    public function index() {
        return view('backend.logo.index');
    }

    public function form($id = null) {
        Unit::addGlobalScope(new DraftScope());

        $logo = LogoModel::where('status', '=', 1)->firstOrNew(['id' => $id]);
        $unit = Unit::where('level', '=', 1)->get();
        $page_title = $id ? 'Logo '. $id : trans('backend.add_new');
        !empty($logo->object) && $id ? $get_logo = json_decode($logo->object) : $get_logo = [];
        return view('backend.logo.form', [
            'logo' => $logo,
            'page_title' => $page_title,
            'unit' => $unit,
            'get_logo' => $get_logo,
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        LogoModel::addGlobalScope(new DraftScope());
        $query = LogoModel::query();
        $query->select([
            'el_logo.*',
        ]);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.logo.edit', ['id' => $row->id]);
            $row->image_url = image_file($row->image);

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

    public function logoOutside() {
        $logo = Config::where('name','=','logo_outside')->first();
        return view('backend.logo.outside',['logo'=>$logo]);
    }

    public function favicon() {
        $favicon = Config::where('name','=','favicon')->first();
        return view('backend.logo.favicon',['favicon'=>$favicon]);
    }

    public function saveFavicon(Request $request) {
        $this->validateRequest([
            'image' => 'required_if:id,',
        ], $request, [
            'image' => 'Hình ảnh',
            'status' => 'Trạng thái'
        ]);
        $model = Config::firstOrNew(['name' => 'favicon']);

        $sizes = config('image.sizes.favicon');
        $model->value = upload_image($sizes, $request->image);

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.logo.favicon')
            ]);
        }
        json_message('Không thể lưu', 'error');
    }

    public static function getLogo($name = 'logo') {
        return Config::where('name','=', $name)->value('value');
    }

    public function save(Request $request) {
        $this->validateRequest([
            'image' => 'required_if:id,',
        ], $request, [
            'image' => 'Hình ảnh',
            'status' => 'Trạng thái'
        ]);
        $model = LogoModel::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $sizes = config('image.sizes.logo');
        $model->image = upload_image($sizes, $request->image);

        $model->object = !empty($request->object) && is_array($request->object) ? json_encode($request->object) : '';
        if (empty($model->id)) $model->created_by = \Auth::id();
        $model->updated_by = \Auth::id();

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.logo')
            ]);
        }
        json_message('Không thể lưu', 'error');
    }

    public function saveLogoOutside(Request $request) {
        $this->validateRequest([
            'image' => 'required_if:id,',
        ], $request, [
            'image' => 'Hình ảnh',
            'status' => 'Trạng thái'
        ]);
        $model = Config::firstOrNew(['name' => 'logo_outside']);

        $sizes = config('image.sizes.logo');
        $model->value = upload_image($sizes, $request->image);

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.logo_outside')
            ]);
        }
        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        LogoModel::destroy($ids);
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
                $model = LogoModel::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = LogoModel::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }
}
