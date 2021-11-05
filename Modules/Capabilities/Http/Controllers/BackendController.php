<?php

namespace Modules\Capabilities\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Capabilities\Entities\Capabilities;
use Modules\Capabilities\Entities\CapabilitiesDictionary;
use Modules\Capabilities\Entities\CapabilitiesGroup;
use Modules\Capabilities\Entities\CapabilitiesCategory;
use Modules\Capabilities\Entities\CapabilitiesCategoryGroup;
use Modules\Capabilities\Entities\CapabilitiesTitle;

class BackendController extends Controller
{
    public function index() {
        return view('capabilities::backend.capabilities.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Capabilities::query();
        $query->select(['a.*', 'b.name AS category_name', 'c.name AS category_group_name', 'd.name AS group_name']);
        $query->from('el_capabilities AS a');
        $query->leftJoin('el_capabilities_category AS b', 'b.id', '=', 'a.category_id');
        $query->leftJoin('el_capabilities_category_group AS c', 'c.id', '=', 'a.category_group_id');
        $query->leftJoin('el_capabilities_group AS d', 'd.id', '=', 'a.group_id');

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('a.name', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.code', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.capabilities.edit', ['id' => $row->id]);

        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {

        $categories = CapabilitiesCategory::get();
        $level = CapabilitiesGroup::get();
        
        if ($id) {
            $model = Capabilities::find($id);
            $groups = CapabilitiesCategoryGroup::where('category_id', '=', $model->category_id)->get();
            $dictionary = CapabilitiesDictionary::where('capabilities_id', '=', $model->id)->first();
            $page_title = $model->name;

            return view('capabilities::backend.capabilities.form', [
                'model' => $model,
                'page_title' => $page_title,
                'categories' => $categories,
                'level' => $level,
                'groups' => $groups,
                'dictionary' => $dictionary,
            ]);
        }

        $model = new Capabilities();
        $dictionary = new CapabilitiesDictionary();
        $page_title = trans('backend.add_new');
        
        return view('capabilities::backend.capabilities.form', [
            'model' => $model,
            'page_title' => $page_title,
            'categories' => $categories,
            'level' => $level,
            'dictionary' => $dictionary,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_capabilities,code,'. $request->id,
            'name' => 'required',
            'category_id' => 'nullable|exists:el_capabilities_category,id',
            'category_group_id' => 'nullable|exists:el_capabilities_category_group,id',
            'group_id' => 'nullable|exists:el_capabilities_group,id',
            'description' => 'nullable'
        ], $request, Capabilities::getAttributeName());

        $model = Capabilities::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        
        if ($model->save()) {
            $dictionary = CapabilitiesDictionary::firstOrNew(['id' => $request->dic_id]);
            $dictionary->fill($request->all());
            $dictionary->capabilities_id = $model->id;
            $dictionary->save();

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.capabilities')
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        $item = CapabilitiesTitle::whereIn('capabilities_id', $ids)->first();
        if ($item){
            json_message('Có dữ liệu liên quan. Không thể xoá', 'error');
        }

        Capabilities::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function ajaxGetGroupName(Request $request){
        $this->validateRequest([
            'category_id' => 'required|exists:el_capabilities_category,id',
        ], $request, [
            'category_id' => 'Danh mục năng lực',
        ]);
        $category_id = $request->category_id;

        $category_id = CapabilitiesCategory::find($category_id);

        $groups = CapabilitiesCategoryGroup::where('category_id', '=', $category_id->id)->get();

        json_result($groups);
    }
}
