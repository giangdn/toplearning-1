<?php

namespace Modules\TrainingAction\Http\Controllers;

use Modules\TrainingAction\Entities\TrainingActionRoles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index () {
        return view('trainingaction::backend.role.index');
    }
    
    public function form($id = null) {
        $model = TrainingActionRoles::firstOrNew(['id' => $id]);
        return view('trainingaction::backend.role.form', [
            'model' => $model,
            'page_title' => $model->name ? $model->name : trans('backend.add_new'),
        ]);
    }
    
    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        
        $query = TrainingActionRoles::query();
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }
        
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        foreach ($rows as $row) {
            $row->name = $row->getLang('name');
            $row->edit_url = route('module.training_action.role.edit', ['id' => $row->id]);
        }
        
        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|string|unique:el_training_action_roles,code|max:50',
            'name' => 'required|string|max:200',
            'status' => 'required|integer|in:0,1',
        ], $request, [
            'code' => trans('backend.code'),
            'name' => trans('backend.name'),
            'status' => trans('backend.status'),
        ]);
        
        $model = TrainingActionRoles::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());
        $model->save();
        
        return response()->json([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.training_action.role'),
        ]);
    }
    
    public function remove(Request $request) {
        $ids = $request->post('ids', null);
        if ($ids) {
            TrainingActionRoles::destroy($ids);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
