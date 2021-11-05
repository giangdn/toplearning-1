<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() {
        return view('backend.setting.index');
    }

    public function closeOpendMenu(Request $request){
        session(['close_open_menu_backend' => $request->status]);
        session()->save();
    }
}
