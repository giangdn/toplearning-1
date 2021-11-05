<?php

namespace App\Http\Controllers;

use App\Permission;
use Composer\Autoload\ClassMapGenerator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\User\Entities\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function callAction($method, $parameters)
    {
        /*$route_name = \Request::route()->getName();
        $permission = \App\Permission::where('code', $route_name)->first();
        if ($permission) {

            $haspermission = \permission($route_name);
            if (empty($haspermission)) {
                $extends = explode(',', $permission->extend);
                foreach ($extends as $extend) {
                    if (\permission(trim($extend))) {
                        $haspermission = true;
                    }
                }
            }

            if ($route_name) {
                if (!$haspermission) {
                    abort(403);
                }
            }
        }*/

        if (\Auth::check()) {
            \Auth::user()->updateAnalytics();
        }


        return parent::callAction($method, $parameters);
    }

    public function validateRequest($rules, Request $request, $attributeNames = null)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($attributeNames) {
            $validator->setAttributeNames($attributeNames);
        }

        if ($validator->fails()) {
            json_message($validator->errors()->all()[0], 'error');
        }
    }

    public function checkSelectUnit(Request $request)
    {
        $check = User::getRoleAndManagerUnitUser();
        $session_user_unit = session()->get('user_unit');
        if (empty($session_user_unit)){
            if(count($check)>1){
                json_result(['modal'=>true]);
            }else{
                \session()->put('user_unit',$check[0]->id);
                \session()->save();
            }
        }else
            json_result(['modal'=>false]);
    }

    public function saveSelectUnit(Request $request)
    {
        $unit = $request->input('unit-select');
        \session()->put('user_unit',$unit);
        \session()->save();

        json_result([
            'status'=>'ok',
            'redirect' => Permission::isUnitManager() ? route('module.dashboard_unit') : route('module.dashboard')
        ]);
    }

    public function getRolesUser()
    {
        $user = \Auth::user();
        $roles = $user->roles()->get();

    }
    public function approve(Request $request)
    {
        $model = $request->model;
        $name = \Str::ucfirst(\Str::camel(substr($model,3)));
        $slash = '\\';
        $modules = \Module::all();
        foreach ($modules as $index => $item) {
            $module = \Module::find($item->name);
            $isDir=is_dir($module->getPath().'/Http/Controllers/Backend');
            if (!$isDir)
                continue;
            $classController = 'Modules'.$slash.$item->name.$slash.'Http'.$slash.'Controllers'.$slash.'Backend'.$slash.$name.'Controller';
            $class_name = class_exists($classController);
            if ($class_name){
                $controller = new $classController();
                return $controller->approve($request);
            }
        };
        return abort(404);
    }
    public function showModalNoteApproved(Request $request)
    {
        $model = $request->model;
        return view('modal.backend.note_approved',
        [
            'model'=>$model
        ]);
    }
    public function showModalStepApproved(Request $request)
    {
        return view('modal.backend.step_approved');
    }
    public function getApprovedStep(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $model_id = $request->input('model_id');
        $model = $request->input('model');
        $query = ApprovedModelTracking::query();
        $query->select(['id','level','status','note','created_by_name','created_at',]);
        $query->where(['model_id'=>$model_id,'model'=>$model]);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->approved_date = get_date($row->created_at, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
