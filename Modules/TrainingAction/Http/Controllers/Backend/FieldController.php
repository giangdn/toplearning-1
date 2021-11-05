<?php

namespace Modules\TrainingAction\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TrainingAction\Entities\TrainingActionField;

class FieldController extends Controller
{
    public function index () {
        return view('trainingaction::backend.field.index');
    }
    
    public function form($id = null) {
        $model = TrainingActionField::firstOrNew(['id' => $id]);
        return view('trainingaction::backend.field.form', [
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
        
        $query = TrainingActionField::query();
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
            $row->edit_url = route('module.training_action.field.edit', ['id' => $row->id]);
            $row->name = $row->getLang('name');
        }
        
        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|string|unique:el_training_action_category,code|max:50',
            'name' => 'required|string|max:200',
            'status' => 'required|integer|in:0,1',
        ], $request, [
            'code' => trans('backend.code'),
            'name' => trans('backend.name'),
            'status' => trans('backend.status'),
        ]);
        
        $model = TrainingActionField::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());
        $model->save();
        
        return response()->json([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.training_action.field'),
        ]);
    }
    
    public function remove(Request $request) {
        $ids = $request->post('ids', null);
        if ($ids) {
            TrainingActionField::destroy($ids);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
