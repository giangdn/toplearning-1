<?php

namespace Modules\TrainingAction\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Modules\TrainingAction\Entities\TrainingActionCategory;
use Illuminate\Http\Request;
use Modules\TrainingAction\Entities\TrainingActionScore;

class CategoryController extends Controller
{
    public function index () {
        return view('trainingaction::backend.category.index');
    }
    
    public function form($id = null) {
        $model = TrainingActionCategory::firstOrNew(['id' => $id]);
        return view('trainingaction::backend.category.form', [
            'model' => $model,
            'page_title' => $model->id ? $model->name : trans('backend.create'),
        ]);
    }
    
    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
    
        $query = TrainingActionCategory::query();
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
            $row->edit_url = route('module.training_action.category.edit', ['id' => $row->id]);
            $row->teachers_url = route('module.training_action.teachers', [$row->id]);
            $row->students_url = route('module.training_action.register', [$row->id]);
            $row->name = $row->getLang('name');
        }
    
        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function getScores(Request $request) {
        $id = $request->get('id');
        $type = $request->get('type');
        
        $rows = TrainingActionScore::where('training_action_id', '=', $id)
            ->where('type', '=', $type)
            ->get()
            ->toArray();
        
        return response()->json($rows);
    }
    
    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|max:100|unique:el_training_action,code,' . $request->post('id'),
            'name' => 'required|string|max:250',
            'status' => 'required|integer|in:0,1',
            'teacher_id' => 'array',
            'teacher_from' => 'required|array',
            'teacher_to' => 'required|array',
            'teacher_score' => 'required|array',
            'student_id' => 'array',
            'student_from' => 'required|array',
            'student_to' => 'required|array',
            'student_score' => 'required|array',
        ], $request, [
            'code' => 'Mã',
            'name' => 'Tên',
            'status' => 'Trạng thái',
        ]);
        
        $teacher_id = $request->post('teacher_id', []);
        $teacher_from = $request->post('teacher_from');
        $teacher_to = $request->post('teacher_to');
        $teacher_score = $request->post('teacher_score');
        $student_id = $request->post('student_id', []);
        $student_from = $request->post('student_from');
        $student_to = $request->post('student_to');
        $student_score = $request->post('student_score');
        
        foreach ($teacher_id as $key => $item) {
            if (empty(@$teacher_from[$key]) || empty(@$teacher_to[$key]) || empty(@$teacher_score[$key])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Thang điểm cho giảng viên không được trống',
                ]);
            }
        }
    
        foreach ($student_id as $key => $item) {
            if (empty(@$student_from[$key]) || empty(@$student_to[$key]) || empty(@$student_score[$key])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Thang điểm cho học viên không được trống',
                ]);
            }
        }
        
        $model = TrainingActionCategory::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());
        
        if ($model->save()) {
            foreach ($teacher_id as $key => $item) {
                $score = TrainingActionScore::firstOrNew(['id' => $item]);
                $score->training_action_id = $model->id;
                $score->type = 1;
                $score->from = $teacher_from[$key];
                $score->to = $teacher_to[$key];
                $score->score = $teacher_score[$key];
                $score->save();
            }
            
            foreach ($student_id as $key => $item) {
                $score = TrainingActionScore::firstOrNew(['id' => $item]);
                $score->training_action_id = $model->id;
                $score->type = 2;
                $score->from = $student_from[$key];
                $score->to = $student_to[$key];
                $score->score = $student_score[$key];
                $score->save();
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => trans('lageneral.successful_save'),
            'redirect' => route('module.training_action.category'),
        ]);
    }
    
    public function remove(Request $request) {
        $ids = $request->post('ids', null);
        if ($ids) {
            TrainingActionCategory::destroy($ids);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
