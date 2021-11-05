<?php

namespace Modules\Capabilities\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Capabilities\Entities\Capabilities;
use Modules\Capabilities\Entities\CapabilitiesGroup;

class LevelController extends Controller
{
    public function index() {
        return view('capabilities::backend.capabilities_level.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CapabilitiesGroup::query();

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
            $row->edit_url = route('module.capabilities.group.edit', ['id' => $row->id]);

        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        if ($id) {
            $model = CapabilitiesGroup::find($id);
            $page_title = $model->name;
           
            return view('capabilities::backend.capabilities_level.form', [
                'model' => $model,
                'page_title' => $page_title,
            ]);
        }

        $model = new CapabilitiesGroup();
        $page_title = trans('backend.add_new');
        
        return view('capabilities::backend.capabilities_level.form', [
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
        ], $request, CapabilitiesGroup::getAttributeName());

        $model = CapabilitiesGroup::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        
        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.capabilities.group.edit', [
                    'id' => $model->id
                ])
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        $item = Capabilities::whereIn('category_group_id', $ids)->first();
        if ($item){
            json_message('Có dữ liệu liên quan. Không thể xoá', 'error');
        }
        CapabilitiesGroup::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
