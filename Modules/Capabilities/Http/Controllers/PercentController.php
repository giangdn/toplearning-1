<?php

namespace Modules\Capabilities\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Capabilities\Entities\CapabilitiesConventionPercent;
use Modules\Capabilities\Entities\CapabilitiesGroupPercent;

class PercentController extends Controller
{
    public function index() {
        return view('capabilities::backend.capabilities_percent.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CapabilitiesGroupPercent::query();

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
            $to_percent = '';
            if (!is_null($row->to_percent)){
                $to_percent = number_format($row->to_percent, 1);
            }
            $row->edit_url = route('module.capabilities.group_percent.edit', ['id' => $row->id]);
            $row->percent = number_format($row->from_percent, 1) . ' %' . ' <i class="fa fa-arrow-right"></i> ' . $to_percent . ' %';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        if ($id) {
            $model = CapabilitiesGroupPercent::find($id);
            $convent = CapabilitiesConventionPercent::where('percent_id', '=', $model->id)->get();
            $page_title = $model->percent_group;

            return view('capabilities::backend.capabilities_percent.form', [
                'model' => $model,
                'page_title' => $page_title,
                'convent' => $convent
            ]);
        }

        $model = new CapabilitiesGroupPercent();
        $page_title = trans('backend.add_new');

        return view('capabilities::backend.capabilities_percent.form', [
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'percent_group' => 'required',
            'to_percent' => 'nullable',
            'from_percent' => 'required|min:0',
        ], $request, CapabilitiesGroupPercent::getAttributeName());

        $convent = $request->name;
        $convent_id = $request->convention_id;
        $to_percent = $request->post('to_percent', null);
        $from_percent = $request->post('from_percent');

        $check1 = CapabilitiesGroupPercent::query();
        $check1->where('from_percent', '<=', $from_percent);
        $check1->where('to_percent', '>=', $from_percent);
        $check1->where('id', '!=', $request->id);
        if ($check1->first()) {
            json_message('Khoảng phần trăm đã tồn tại', 'error');
        }

        if ($to_percent) {
            $check2 = CapabilitiesGroupPercent::query();
            $check2->where('from_percent', '<=', $to_percent);
            $check2->where('to_percent', '>=', $to_percent);
            $check2->where('id', '!=', $request->id);
            if ($check2->first()) {
                json_message('Khoảng phần trăm đã tồn tại', 'error');
            }
        }

        $model = CapabilitiesGroupPercent::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if (!is_null($to_percent)){
            if($to_percent  > 100 || $from_percent < 0){
                json_message('Khoảng phần trăm trong khoảng 0 đến 100', 'error');
            }

            if($to_percent < $from_percent){
                json_message('Khoảng phần trăm không hợp lệ', 'error');
            }
        }

        if ($model->save()) {

            if($convent){
                foreach($convent as $convent_key => $name){
                    $conventions = CapabilitiesConventionPercent::firstOrNew(['id' => $convent_id[$convent_key]]);
                    $conventions->percent_id = $model->id;
                    if(isset($name)){
                        $conventions->name = $name;
                        $conventions->save();
                    }
                }
            }

            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.capabilities.group_percent')
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        CapabilitiesGroupPercent::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function removeConvention($percent_id, Request $request) {
        $this->validateRequest([
            'convent_id' => 'required'
        ], $request);

        $convent_id = $request->convent_id;

        CapabilitiesConventionPercent::where('id', '=', $convent_id)->where('percent_id', '=', $percent_id)->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
