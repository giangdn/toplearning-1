<?php

namespace Modules\TrainingAction\Http\Controllers\Backend;

use Modules\TrainingAction\Entities\TrainingActionCategory;
use Modules\TrainingAction\Entities\TrainingActionRegister;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index($training_action) {
        $training_action = TrainingActionCategory::where('id', '=', $training_action)
            ->firstOrFail();
        return view('trainingaction::backend.register.index', [
            'training_action' => $training_action
        ]);
    }
    
    public function form($training_action, $id = null) {
        $model = TrainingActionRegister::firstOrNew(['id' => $id]);
        
        return view('trainingaction::backend.register.form', [
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
        
        $query = TrainingActionRegister::query();
        $query->select([
            'a.*',
            'b.code',
            'b.firstname',
            'b.lastname',
            'c.name AS unit_name',
            'd.name AS unit_manager',
        ]);
        
        $query->from('el_training_action_teachers AS a');
        $query->join('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_unit AS c', 'c.code', '=', 'b.unit_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('a.training_action_id', '=', $training_action);
        
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('b.code', 'like', '%'. $search .'%');
                $subquery->orWhere(\DB::raw('CONCAT_WS(\' \', firstname, lastname)'), 'like', '%'. $search .'%');
            });
        }
        
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function save(Request $request) {
        $this->validateRequest([
        
        ], $request);
    }
    
    public function remove($training_action, Request $request) {
        $this->validateRequest([
            'ids' => 'required|array',
        ], $request, [
            'ids' => trans('app.register'),
        ]);
        
        $ids = $request->post('ids', null);
        $ids = TrainingActionRegister::whereIn('id', $ids)
            ->where('training_action_id', '=', $training_action)
            ->pluck('id')
            ->toArray();
    
        TrainingActionRegister::destroy($ids);
        
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
        
        TrainingActionRegister::whereIn('id', $request->post('ids'))
            ->where('training_action_id', '=', $training_action)
            ->update([
                'status' => $request->post('status')
            ]);
    }
}
