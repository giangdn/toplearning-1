<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SettingTimeModel;
use App\SettingTimeObjectModel;
use App\Models\Categories\Unit;

class SettingTimeController extends Controller
{
    public function index() {
        return view('backend.setting_time.index');
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        $query = SettingTimeObjectModel::query();
        $query->select([
            'el_setting_time_object.*',
        ]);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.setting_time.edit', ['id' => $row->id]);
            if ($row->object != 'All') {
                $objects = json_decode($row->object);
                foreach ($objects as $key => $object) {
                    $unit = Unit::select('name')->where('id',$object)->first();
                    $unit_name[] = $unit->name;
                }
                $row->object = $unit_name;
            }
            $get_times = SettingTimeModel::where('object',$row->id)->get();
            foreach ($get_times as $key => $get_time) {
                if ($get_time->session == 'morning') {
                    $row->start_time_morning = $get_time->start_time;
                    $row->end_time_morning = $get_time->end_time;
                    $row->value_morning = $get_time->value;
                }
                if ($get_time->session == 'noon') {
                    $row->start_time_noon = $get_time->start_time;
                    $row->end_time_noon = $get_time->end_time;
                    $row->value_noon = $get_time->value;
                }
                if ($get_time->session == 'afternoon') {
                    $row->start_time_afternoon = $get_time->start_time;
                    $row->end_time_afternoon = $get_time->end_time;
                    $row->value_afternoon = $get_time->value;
                }
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null) {
        $settingTimeObject = SettingTimeObjectModel::firstOrNew(['id' => $id]);
        $unit = Unit::where('level', '=', 1)->get();
        $page_title = $id ? 'Chỉnh sửa'. $id : trans('backend.add_new');
        !empty($settingTimeObject->object) && $id ? $get_object = json_decode($settingTimeObject->object) : $get_object = [];
        $get_time_morning = SettingTimeModel::where('session','morning')->where('object',$settingTimeObject->id)->first();
        $get_time_noon = SettingTimeModel::where('session','noon')->where('object',$settingTimeObject->id)->first();
        $get_time_afternoon = SettingTimeModel::where('session','afternoon')->where('object',$settingTimeObject->id)->first();
        return view('backend.setting_time.form', [
            'page_title' => $page_title,
            'unit' => $unit,
            'get_time_morning' => $get_time_morning,
            'get_time_noon' => $get_time_noon,
            'get_time_afternoon' => $get_time_afternoon,
            'get_object' => $get_object,
            'settingTimeObject' => $settingTimeObject
        ]);
    }

    public function save(Request $request) {
        if ( ($request->start_time_morning >= $request->start_time_noon || $request->start_time_morning >= $request->start_time_afternoon) ||
             ($request->start_time_noon >= $request->end_time_afternoon || $request->start_time_noon >= $request->start_time_afternoon) || 
             ($request->end_time_morning >= $request->end_time_noon || $request->end_time_morning >= $request->end_time_afternoon) || 
             ($request->end_time_morning >= $request->start_time_noon || $request->end_time_morning >= $request->start_time_afternoon) ) {
            json_message('Thời gian không hợp lệ', 'error');
        }
        $model = SettingTimeObjectModel::firstOrNew(['id' => $request->id]);
        if ($request->object) {
            $model->object = json_encode($request->object);
        } else {
            $model->object = 'All';
        }
        $save_object = $model->save();

        $morning = SettingTimeModel::firstOrNew(['session' => 'morning','object' => $model->id]);
        $morning->value = $request->value_morning;
        $morning->start_time = $request->start_time_morning;
        $morning->end_time = $request->end_time_morning;
        $morning->object = $model->id;
        $morning->session = 'morning';
        $save_time_morning = $morning->save();
    
        $noon = SettingTimeModel::firstOrNew(['session' => 'noon','object' => $model->id]);
        $noon->value = $request->value_noon;
        $noon->start_time = $request->start_time_noon;
        $noon->end_time = $request->end_time_noon;
        $noon->object = $model->id;
        $noon->session = 'noon';
        $save_time_noon = $noon->save();

        $afternoon = SettingTimeModel::firstOrNew(['session' => 'afternoon','object' => $model->id]);
        $afternoon->value = $request->value_afternoon;
        $afternoon->start_time = $request->start_time_afternoon;
        $afternoon->end_time = $request->end_time_afternoon;
        $afternoon->object = $model->id;
        $afternoon->session = 'afternoon';
        $save_time_afternoon = $afternoon->save();

        if ($save_time_morning || $save_time_noon || $save_time_afternoon) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.setting_time')
            ]);
        }

        json_message('Không thể lưu', 'error');
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        foreach ($ids as $key => $id) {
            SettingTimeObjectModel::where('id',$id)->delete();
            SettingTimeModel::where('object',$id)->delete();
        }
        json_message('Đã xóa thành công');
    }
}
