<?php

namespace Modules\TrainingAction\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\TrainingAction\Entities\TrainingActionField;
use Modules\TrainingAction\Entities\TrainingActionPersonCharge;
use Modules\TrainingAction\Entities\TrainingActionPersonChargeRole;
use Modules\TrainingAction\Entities\TrainingActionRoles;
use Illuminate\Http\Request;
use App\Profile;

class PersonChargeController extends Controller
{
    public function index () {
        return view('trainingaction::backend.person-charge.index');
    }
    
    public function form($id = null) {
        $model = TrainingActionPersonCharge::firstOrNew(['id' => $id]);
        $fields = TrainingActionField::all();
        $roles = TrainingActionRoles::all();
        
        $field = false;
        $role = false;
        $profile = false;
        if ($model->id) {
            $profile = Profile::firstOrNew(['user_id' => $model->user_id]);
            $field = TrainingActionField::where('id', '=', $model->field_id)->first();
            $role = TrainingActionPersonChargeRole::where('person_charge_id', '=', $model->id)->first();
        }
        
        return view('trainingaction::backend.person-charge.form', [
            'model' => $model,
            'page_title' => $model->id ? $profile->getFullName() : trans('backend.create'),
            'fields' => $fields,
            'roles' => $roles,
            'role' => $role,
            'field' => $field,
            'profile' => $profile,
        ]);
    }
    
    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        
        $prefix = \DB::getTablePrefix();
        $query = TrainingActionPersonCharge::query();
        $query->select([
            'a.*',
            'b.code',
            \DB::raw('CONCAT('. $prefix .'b.lastname, \' \', '. $prefix .'b.firstname) AS fullname'),
            'c.name AS field_name',
        ]);
        
        $query->from('el_person_charge AS a');
        $query->join('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->join('el_training_action_fields AS c', 'c.id', '=', 'a.field_id');
        
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere(\DB::raw('CONCAT(\'b.lastname\', \' \', \'b.firstname\')'), 'like', '%'. $search .'%');
            });
        }
        
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        foreach ($rows as $row) {
            $row->edit_url = route('module.training_action.person_charge.edit', ['id' => $row->id]);
        }
        
        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function save(Request $request) {
        $this->validateRequest([
            'user_id' => 'required|exists:el_profile,user_id',
            'field_id' => 'required|exists:el_training_action_fields,id',
            'roles' => 'required|array',
            'max_support' => 'required|numeric',
            'type' => 'required|integer|in:1,2',
            'status' => 'required|integer|in:0,1',
        ], $request, [
            'user_id' => trans('backend.user'),
            'field_id' => trans('backend.field'),
            'role_id' => trans('backend.role'),
            'max_support' => trans('backend.max_support'),
            'type' => trans('backend.type'),
            'status' => trans('backend.status'),
        ]);
        
        $model = TrainingActionPersonCharge::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());
        
        if ($model->save()) {
            $roles = $request->post('roles', []);
            foreach ($roles as $role) {
                $uprole = TrainingActionPersonChargeRole::firstOrNew([
                    'person_charge_id' => $model->id,
                    'role_id' => $role
                ]);
                
                $uprole->save();
            }
            
            $ids = TrainingActionPersonChargeRole::where('person_charge_id', '=', $model->id)
                ->whereNotIn('role_id', $roles)
                ->pluck('id')
                ->toArray();
            TrainingActionPersonChargeRole::destroy($ids);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success'),
            'redirect' => route('module.training_action.person_charge'),
        ]);
    }
    
    public function remove(Request $request) {
        $ids = $request->post('ids', null);
        if ($ids) {
            TrainingActionPersonCharge::destroy($ids);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => trans('backend.delete_success'),
        ]);
    }
}
