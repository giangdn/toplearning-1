<?php

namespace Modules\TrainingAction\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\TrainingAction\Entities\TrainingActionCategory;
use Modules\TrainingAction\Entities\TrainingActionRegister;
use Modules\TrainingAction\Entities\TrainingActionTeacher;

class ListController extends Controller
{
    public function index() {
        $items = TrainingActionCategory::where('status', '=', 1)
            ->paginate(10);
        
        return view('trainingaction::frontend.list', [
            'items' => $items
        ]);
    }
    
    public function registerStudent(Request $request) {
        $this->validateRequest([
            'training_action_id' => 'required',
        ], $request, [
            'training_action_id' => trans('backend.training_action'),
        ]);
        
        $model = new TrainingActionRegister();
        $model->training_action_id = $request->post('training_action_id');
        $model->user_id = \Auth::id();
        $model->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký thành công',
        ]);
    }
    
    public function registerTeacher(Request $request) {
        $this->validateRequest([
            'training_action_id' => 'required',
        ], $request, [
            'training_action_id' => trans('backend.training_action'),
        ]);
    
        $model = new TrainingActionTeacher();
        $model->training_action_id = $request->post('training_action_id');
        $model->user_id = \Auth::id();
        $model->status = 2;
        $model->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký thành công',
        ]);
    }
}
