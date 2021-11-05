<?php

namespace Modules\TrainingAction\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\TrainingAction\Entities\TrainingActionCategory;
use Modules\TrainingAction\Entities\TrainingActionTeacher;

class TeacherController extends Controller
{
    public function index($training_action) {
        $training_action = TrainingActionCategory::where('id', '=', $training_action)
            ->firstOrFail();
        return view('trainingaction::backend.teacher.index', [
            'training_action' => $training_action
        ]);
    }
    
    public function form($training_action, $id = null) {
        $model = TrainingActionTeacher::firstOrNew(['id' => $id]);
        
        return view('trainingaction::backend.teacher.form', [
            'model' => $model,
            'page_title' => $model->id ? $model->name : trans('backend.create'),
        ]);
    }
    
    public function getData($training_action, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        
        $query = TrainingActionTeacher::query();
        $query->select([
            'a.*',
            'b.code',
            'b.firstname',
            'b.lastname',
        ]);
        $query->from('el_training_action_teachers AS a');
        $query->join('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->where('a.training_action_id', '=', $training_action);
        
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('b.code', 'like', '%'. $search .'%');
                $subquery->orWhere(\DB::raw('CONCAT_WS(\' \', firstname, lastname)'), 'like', '%'. $search .'%');
            });
        }
        
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function save(Request $request) {
    
    }
    
    public function remove($training_action, Request $request) {
        $ids = $request->post('ids', null);
        if ($ids) {
            $ids = TrainingActionTeacher::whereIn('id', $ids)
                ->where('training_action_id', '=', $training_action)
                ->pluck('id')
                ->toArray();
            
            TrainingActionTeacher::destroy($ids);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
    
    public function approve($training_action, Request $request) {
        $this->validateRequest([
            'ids' => 'required|array',
            'status' => 'required|in:0,1',
        ], $request, [
            'ids' => trans('backend.training_action_teachers'),
            'status' => trans('backend.status'),
        ]);
        
        TrainingActionTeacher::whereIn('id', $request->post('ids'))
            ->where('training_action_id', '=', $training_action)
            ->update([
                'status' => $request->post('status')
            ]);
    }
}
