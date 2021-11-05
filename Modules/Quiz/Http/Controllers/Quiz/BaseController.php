<?php

namespace Modules\Quiz\Http\Controllers\Quiz;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    protected function getUserType() {
        if (\Auth::check()) {
            return 1;
        }
        
        if (\Auth::guard('secondary')->check()) {
            return 2;
        }
        
        return null;
    }
    
    protected function getUserId() {
        if (\Auth::check()) {
            return \Auth::id();
        }
        
        if (\Auth::guard('secondary')->check()) {
            return \Auth::guard('secondary')->id();
        }
        
        return null;
    }
}
