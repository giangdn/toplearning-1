<?php

namespace Modules\TrainingPlan\Http\Controllers;

use App\Models\Categories\Course;
use App\Profile;
use App\Scopes\DraftScope;
use App\ViewModels\CourseRegisterViewModel;
use BaconQrCode\Common\Mode;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Categories\TrainingProgram;
use Modules\TrainingPlan\Entities\TrainingPlan;
use App\Http\Controllers\Controller;
use App\Models\Categories\Unit;
use App\TypeCost;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\TrainingForm;
use Modules\TrainingPlan\Entities\TrainingPlanDetail;

class BackendController extends Controller
{
    public function index() {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        // return view('trainingplan::backend.plan.index');
        return view('backend.training.index',[
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $search_year = $request->input('year');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        TrainingPlan::addGlobalScope(new DraftScope());
//        $query = TrainingPlan::select('el_training_plan.*')->from('el_training_plan')->where('status', '=', 1);
        $query= TrainingPlan::query();
        $query->select(['el_training_plan.*','u.name as unit_name','u.code as unit_code']);
        $query->from('el_training_plan');
        $query->leftJoin('el_unit as u','u.id','=','el_training_plan.unit_id');

        if ($search) {
            $query->where('el_training_plan.code', '=', $search);
            $query->orWhere('u.code', 'like', '%'. $search .'%');
            $query->orWhere('u.name', 'like', '%'. $search .'%');
            $query->orWhere('el_training_plan.code', 'like', '%'. $search .'%');
            $query->orWhere('el_training_plan.code', 'like', '%'. $search .'%');
        }

        if ($search_year) {
            $query->where('el_training_plan.year', '=', $search_year);
        }

        $count = $query->count();
        $query->orderBy('el_training_plan.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('module.training_plan.edit', ['id' => $row->id]);
            $row->plan_url = route('module.training_plan.detail', ['id' => $row->id]);
            $row->unit_name = $row->unit_code . '-' . $row->unit_name;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $get_type_costs = TypeCost::get();
        $training_forms = TrainingForm::get();

        $query = TrainingCost::query();
        $query->select(['a.*','b.name as type_cost_name','b.id as type_cost_id']);
        $query->from('el_training_cost as a');
        $query->leftjoin('el_type_cost as b','b.id','=','a.type');
        $query->orderBy('b.id','asc');
        $get_training_type_costs = $query->get();
        $get_training_type_costs_array = $query->pluck('id')->toArray();

        if ($id) {
            $model = TrainingPlan::find($id);
            $page_title = $model->name;
            $unit = Unit::findOrFail($model->unit_id);

            $array_type_cost = TrainingCost::pluck('id')->toArray();

            $get_type_model_costs = json_decode($model->type_costs);
            foreach($get_type_model_costs as $item) {
                $get_type_cost_id[] = $item->id;
            }

            $type_costs_new = [];
            if( !empty(array_diff($array_type_cost, $get_type_cost_id)) ) {
                $result = array_diff($array_type_cost, $get_type_cost_id);
                $type_costs = TrainingCost::query();
                $type_costs->select(['a.*','b.name as type_cost_name','b.id as type_cost_id']);
                $type_costs->from('el_training_cost as a');
                $type_costs->leftjoin('el_type_cost as b','b.id','=','a.type');
                $type_costs->whereIn('a.id',$result);
                $type_costs_new = $type_costs->get();
            }

            return view('trainingplan::backend.plan.form', [
                'model' => $model,
                'page_title' => $page_title,
                'unit' => $unit,
                'get_training_type_costs' => $get_training_type_costs,
                'training_forms' => $training_forms,
                'get_training_type_costs_array' => $get_training_type_costs_array,
                'array_type_cost' => $array_type_cost,
                'get_type_cost_id' => $get_type_cost_id,
                'type_costs_new' => $type_costs_new,
                'get_menu_child' => $get_menu_child,
                'name_url' => 'training_organizations',
            ]);

        } else {
            $model = new TrainingPlan();
            $page_title = trans('backend.add_new') ;
        }
        return view('trainingplan::backend.plan.form', [
            'model' => $model,
            'page_title' => $page_title,
            'get_training_type_costs' => $get_training_type_costs,
            'training_forms' => $training_forms,
            'get_training_type_costs_array' => $get_training_type_costs_array,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_training_plan,code,'. $request->id,
            'name' => 'required',
            'status' => 'required',
        ], $request, TrainingPlan::getAttributeName());

        foreach($request->id_costs_plan as $key => $id_cost_plan) {
            $object = new \StdClass();
            $object->id = $id_cost_plan;
            $object->set_cost = $request->costs_plan_detail[$key] ? str_replace(',', '', $request->costs_plan_detail[$key]) : 0;
            $object->type_cost_id = $request->id_type_costs_plan[$key];
            $object->explan = $request->explan[$key] ? $request->explan[$key] : '';
            $object->training_form_id = !empty($request->{'training_form_'. $id_cost_plan}) ? $request->{'training_form_'. $id_cost_plan} : [] ;
            $all_type_costs[] = $object;
        }
        $json_all_type_costs = json_encode($all_type_costs);

        $model = TrainingPlan::firstOrNew(['id' => $request->id]);
        $model->type_costs = $json_all_type_costs;
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.training_plan.edit', [
                    'id' => $model->id
                ])
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        $check_training_plan = TrainingPlanDetail::whereIn('plan_id',$ids)->get();
        if( $check_training_plan->isEmpty() ) {
            TrainingPlan::destroy($ids);
            json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
        } else {
            json_message('Không thể xóa vì kế hoạch có chi tiết');
        }

    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Kế hoạch đào tạo',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = TrainingPlan::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = TrainingPlan::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('lageneral.successful_save')
        ]);
    }
}
