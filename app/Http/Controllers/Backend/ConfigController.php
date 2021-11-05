<?php

namespace App\Http\Controllers\Backend;

use App\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index() {
        return view('backend.config.index', [
        
        ]);
    }
    
    public function save(Request $request) {
        $configs = $request->only(Config::configNames());
        foreach ($configs as $key => $config) {
            Config::setConfig($key, $config);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success')
        ]);
    }
    
    public function load(Request $request) {
        $form = $request->get('form');
        if (!view()->exists('backend.config.form.' . $form)) {
            return '';
        }
        
        return view('backend.config.form.' . $form);
    }
}
