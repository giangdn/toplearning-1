<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Config;

class SettingColorController extends Controller
{
    public function index() {
        $model = Config::where('name','setting_color')->first();
        $hover_color_button = Config::where('name','setting_hover_color')->first();
        if(empty($model)) {
            $model = new Config();
        }
        if(empty($hover_color_button)) {
            $hover_color_button = new Config();
        }
        return view('backend.setting_color.form', [
            'model' => $model,
            'hover_color_button' => $hover_color_button
        ]);
    }

    public function save(Request $request) {
        if ($request->color_button) {
            $model = Config::firstOrNew(['name' => 'setting_color']);
            $model->value = $request->color_button;
            $save_color = $model->save();
        }
        
        if ($request->hover_color_button) {
            $hover_color_button = Config::firstOrNew(['name' => 'setting_hover_color']);
            $hover_color_button->value = $request->hover_color_button;
            $save_hover_color = $hover_color_button->save();
        }
        if ($save_color || $save_hover_color) {
            json_result([
                'status' => 'success',
                'message' => 'Lưu thành công',
                'redirect' => route('backend.setting_color')
            ]);
        }

        json_message('Không thể lưu', 'error');

    }
}
