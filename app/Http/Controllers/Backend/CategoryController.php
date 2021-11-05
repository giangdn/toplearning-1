<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Http\Controllers\Controller;
use App\ProfileView;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $max_level = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $max_level_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level){
            return Area::getLevelName($level);
        };
        return view('backend.category.index', [
            'max_level' => $max_level,
            'level_name' => $level_name,
            'max_level_area' => $max_level_area,
            'level_name_area' => $level_name_area,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function dashboard() {
        return redirect()->route('module.dashboard');
    }

    public function getUserCreateUpdated(Request $request){
        if($request->created) {
            $user = ProfileView::where('user_id',$request->created)->first();
        } else {
            $user = ProfileView::where('user_id',$request->updated)->first();
        }

        return view('backend.modal.modal_user_created_updated', [
            'user' => $user,
        ]);
    }
}
