<?php

namespace Modules\TrainingPlan\Http\Controllers;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\TrainingForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use Modules\TrainingPlan\Entities\TrainingPlanDetail;
use Modules\TrainingPlan\Entities\TrainingPlan;
use App\Http\Controllers\Controller;
use Modules\TrainingPlan\Imports\TrainingPlanDetailImport;
use Modules\TrainingPlan\Exports\ExportTrainingPlanDetail;
use Modules\TrainingPlan\Exports\ExportTemplateTraining;
use App\Models\Categories\TrainingObject;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\Unit;
use App\TypeCost;
use App\Models\Categories\TrainingCost;
use Modules\TrainingPlan\Entities\TrainingPlanDetailTypeCost;

class PlanDetailController extends Controller
{
    public function index($plan_id, Request $request) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $errors = session()->get('errors');
        \Session::forget('errors');
        $type_costs = TypeCost::get();
        $count_type_costs = count($type_costs);
        return view('trainingplan::backend.plan-detail.index',[
            'plan_id' => $plan_id,
            'errors' => $errors,
            'type_costs' => $type_costs,
            'count_type_costs' => $count_type_costs,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function getData($plan_id, Request $request) {
        $search = $request->input('search');
        $training_program_id = $request->input('training_program_id');
        $level_subject_id = $request->input('level_subject_id');
        $course_type = $request->input('course_type');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = TrainingPlanDetail::query();
        $query->select([
            'el_training_plan_detail.*',
            'b.name AS program_name',
            'c.name AS subject_name',
            'c.code',
            'e.name as level_subject_name',
        ]);
        $query->from('el_training_plan_detail AS el_training_plan_detail');
        $query->where('el_training_plan_detail.plan_id', '=', $plan_id);
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_training_plan_detail.training_program_id');
        $query->leftJoin('el_subject AS c', 'c.id', '=', 'el_training_plan_detail.subject_id');
        $query->leftJoin('el_level_subject as e', 'e.id', '=', 'el_training_plan_detail.level_subject_id');

        if ($search) {
            $query->where(function($sub) use ($search){
                $sub->where('c.name', 'like', '%'. $search .'%');
                $sub->orwhere('c.code', 'like', '%'. $search .'%');
            });
        }
        if ($training_program_id){
            $query->where('el_training_plan_detail.training_program_id', '=',  $training_program_id);
        }
        if ($level_subject_id){
            $query->where('el_training_plan_detail.level_subject_id', '=', $level_subject_id);
        }
        if ($course_type){
            $query->where('el_training_plan_detail.course_type','like','%'.$course_type.'%');
        }

        $count = $query->count();
        $query->orderBy('el_training_plan_detail.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.training_plan.detail.edit', ['id' => $plan_id, 'plan_detail_id' => $row->id]);
            $get_type_model_costs = json_decode($row->type_costs);

            $row->exis_training_CBNV = $row->exis_training_CBNV ? number_format($row->exis_training_CBNV, 0) : 0;
            $row->recruit_training_CBNV = $row->recruit_training_CBNV ? number_format($row->recruit_training_CBNV, 0) : 0;
            $row->total_type_cost = $row->total_type_cost ? number_format($row->total_type_cost, 0) : 0;

            $course_type = explode(',',$row->course_type);
            $row->course_type = (in_array(1,$course_type) ? 'Đào tạo trực tuyến,' : '') . (in_array(2,$course_type) ? ' Đào tạo Tập trung,' : '');
            $type_costs = TypeCost::get();
            foreach($type_costs as $key => $type_cost) {
                if( !empty($get_type_model_costs[$key]) ) {
                    $row->{'type_cost_'. $type_cost->id} = number_format($get_type_model_costs[$key]->money_cost);
                } else {
                    $row->{'type_cost_'. $type_cost->id} = 0;
                }
            }

            $training_form_array = [];
            $get_training_forms =  TrainingForm::whereIn('id',explode(',',$row->training_form_id))->get();
            foreach ($get_training_forms as $key => $get_training_form) {
                $training_form_array[] = $get_training_form->name;
            }
            $row->training_form = implode(', ',$training_form_array);

        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($plan_id, $id = 0) {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        \Session::forget('checkTypeCost');
        \Session::forget('uncheckTypeCost');
        $get_training_plan_type_costs = TrainingPlan::select('type_costs')->where('id',$plan_id)->where('status',1)->first();
        $array_training_plan_type_costs = json_decode($get_training_plan_type_costs->type_costs);
        // dd($array_training_plan_type_costs);
        $get_type_costs = TypeCost::get();
        $training_objects = TrainingObject::where('status',1)->get();
        $training_partners = TrainingPartner::get();
        $units = Unit::where('status', '=', 1)->get();
        $training_forms = TrainingForm::get();

        $array_type_cost = TypeCost::pluck('id')->toArray();
        
        if ($id) {
            $model = TrainingPlanDetail::find($id);
            $subject = Subject::find($model->subject_id);

            $subjects = Subject::where('training_program_id',$model->training_program_id)->get();

            $training_program = TrainingProgram::find($model->training_program_id);
            $level_subject = LevelSubject::find($model->level_subject_id);
            $course_type = explode(',', $model->course_type);
            $training_form_id = explode(',', $model->training_form_id);
            $training_object_id = explode(',', $model->training_object_id);
            $training_partner = explode(',', $model->training_partner);
            $responsable = explode(',', $model->responsable);
            $page_title = $subject->name;

            $get_type_model_costs = json_decode($model->type_costs);
            foreach($get_type_model_costs as $item) {
                $get_type_cost_id[] = $item->id;
            }
            $type_costs_new = [];
            if(!empty(array_diff($array_type_cost, $get_type_cost_id))) {
                $result = array_diff($array_type_cost, $get_type_cost_id);
                foreach($result as $iteam_result) {
                    $type_cost_diff = TypeCost::where('id',$iteam_result)->first();
                    $type_costs_new[] = [$type_cost_diff->id,$type_cost_diff->name];
                }
            } 
            return view('trainingplan::backend.plan-detail.form', [
                'model' => $model,
                'page_title' => $page_title,
                'plan_id' => $plan_id,
                'training_program' => $training_program,
                'subject' => $subject,
                'training_forms' => $training_forms,
                'training_form_id' => $training_form_id,
                'level_subject' => $level_subject,
                'units' => $units,
                'training_objects' => $training_objects,
                'training_object_id' => $training_object_id,
                'training_partners' => $training_partners,
                'training_partner' => $training_partner,
                'responsable' => $responsable,
                'subjects' => $subjects,
                'type_costs' => $get_type_costs,
                'course_type' => $course_type,
                'array_training_plan_type_costs' => $array_training_plan_type_costs,
                'array_type_cost' => $array_type_cost,
                'get_type_cost_id' => $get_type_cost_id,
                'type_costs_new' => $type_costs_new,
                'get_menu_child' => $get_menu_child,
                'name_url' => 'training_organizations',
            ]);
        }

        $model = new TrainingPlanDetail();
        $page_title = trans('backend.add_new') ;

        return view('trainingplan::backend.plan-detail.form', [
            'model' => $model,
            'page_title' => $page_title,
            'plan_id' => $plan_id,
            'training_objects' => $training_objects,
            'units' => $units,
            'training_partners' => $training_partners,
            'type_costs' => $get_type_costs,
            'training_forms' => $training_forms,
            'array_type_cost' => $array_type_cost,
            'get_menu_child' => $get_menu_child,
            'name_url' => 'training_organizations',
        ]);
    }

    public function save($plan_id, Request $request) {
        $this->validateRequest([
            'training_program_id' => 'required',
            'subject_id' => 'required',
            'course_type' => 'required',
            'periods' => 'required',
            'total_course' => 'required',
            'total_student'=> 'required',
        ], $request, TrainingPlanDetail::getAttributeName());
        // dd($request->periods);
        foreach($request->hidden_type_cost_id as $key => $id_cost_plan_detail) {
            $object = new \StdClass();
            $object->id = str_replace(',', '', $id_cost_plan_detail);
            $object->money_cost = str_replace(',', '', $request->money_costs_plan_detail[$key]);
            $all_type_costs[] = $object;
        }
        $json_all_type_costs = json_encode($all_type_costs);
        
        foreach ($request->money_costs_plan_detail as $key => $money_costs_plan_detail) {
            $money_costs_plan[] = str_replace(',', '', $money_costs_plan_detail);
        }
        
        $total_type_cost = array_sum($money_costs_plan);
        $training_object_id = $request->training_object_id;
        $course_type = $request->course_type;
        $training_form_id = $request->training_form_id;
        $training_partner = $request->training_partner;
        $responsable = $request->responsable;

        if (empty($request->id)){
            $check = TrainingPlanDetail::query();
            $check->where('plan_id', '=', $plan_id);
            $check->where('training_program_id', '=', $request->input('training_program_id'));
            $check->where('subject_id', '=', $request->input('subject_id'));
            $check->where('course_type', '=', $request->input('course_type'));
            if ($check->first()){
                json_message('Chi tiết kế hoạch đã tồn tại', 'error');
            }
        }

        $model = TrainingPlanDetail::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->plan_id = $plan_id;
        $model->training_object_id = is_array($training_object_id) ? implode(',', $training_object_id) : null;
        $model->course_type = is_array($course_type) ? implode(',', $course_type) : null;
        $model->training_form_id = is_array($training_form_id) ? implode(',', $training_form_id) : null;
        $model->training_partner = is_array($training_partner) ? implode(',', $training_partner) : null;
        $model->responsable = is_array($responsable) ? implode(',', $responsable) : null;
        $model->type_costs = $json_all_type_costs;
        $model->total_type_cost = $total_type_cost;
        $save_training_plan_detail = $model->save();
        
        if(session()->get('checkTypeCost')) {
            foreach(session()->get('checkTypeCost') as $checkTypeCost) {
                $query = TrainingPlanDetailTypeCost::firstOrNew(['cost_id'=>$checkTypeCost,'training_plan_id'=>$plan_id,'training_plan_detail_id' => $model->id]);
                $query->status = 1;
                $query->training_plan_id = $plan_id;
                $query->cost_id = $checkTypeCost;
                $query->training_plan_detail_id = $model->id;
                $query->save();
            }
        }
        if(session()->get('uncheckTypeCost')) {
            foreach(session()->get('uncheckTypeCost') as $uncheckTypeCost) {
                $query = TrainingPlanDetailTypeCost::firstOrNew(['cost_id'=>$uncheckTypeCost,'training_plan_id'=>$plan_id,'training_plan_detail_id' => $model->id]);
                $query->status = 0;
                $query->training_plan_id = $plan_id;
                $query->cost_id = $uncheckTypeCost;
                $query->training_plan_detail_id = $model->id;
                $query->save();
            }
        }

        if ($save_training_plan_detail) {
            json_result([
                'status' => 'success',
                'message' => trans('lageneral.successful_save'),
                'redirect' => route('module.training_plan.detail', [
                    'id' => $plan_id,
                ])
            ]);
        }

        json_message(trans('lageneral.save_error'), 'error');
    }

    public function remove($plan_id, Request $request) {
        $ids = $request->input('ids', null);
        foreach($ids as $id) {
            TrainingPlanDetail::where('id',$id)->delete();
        }
        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }

    public function importPlanDetail($plan_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new TrainingPlanDetailImport($plan_id);
        \Excel::import($import, $request->file('import_file'));
        
        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import thành công',
            'redirect' => route('module.training_plan.detail', ['id' => $plan_id])
        ]);
    }

    public function exportPlanDetail($plan_id, Request $request)
    {
        return (new ExportTrainingPlanDetail($plan_id))->download('danh_sach_chi_tiet_ke_hoach_dao_tao_'. date('d_m_Y') .'.xlsx');
    }

    //EXPORT TEMPLATE
    public function exportTemplate($plan_id, Request $request)
    {
        return (new ExportTemplateTraining($plan_id))->download('mau_chi_tiet_ke_hoach_dao_tao_'. date('d_m_Y') .'.xlsx');
    }

    public function ajaxLevelSubject(Request $request) {
        $training_program_id = $request->training_program_id;
        $subjects = Subject::where('training_program_id',$training_program_id)->get();
        json_result($subjects);
    }

    //TÍNH CHI PHÍ
    public function ajaxCostCalculate($plan_id, Request $request){
        $get_training_plan_type_costs = TrainingPlan::select('type_costs')->where('id',$plan_id)->where('status',1)->first();
        $training_forms_id = !empty($request->training_form) ? $request->training_form : [];
        $total = $request->total;
        $sum = [];
        
        if (!empty($get_training_plan_type_costs->type_costs)) {
            $array_training_plan_type_costs = json_decode($get_training_plan_type_costs->type_costs);
            foreach($array_training_plan_type_costs as $key => $item) {
                if(!empty(array_intersect($training_forms_id,$item->training_form_id))){
                    if (isset($sum[$item->type_cost_id])) {
                        $sum[$item->type_cost_id] += $item->set_cost;
                    } else {
                        $sum[$item->type_cost_id] = $item->set_cost;
                    }
                } 
            }
        } 
        $sum_training_plans = $sum;
        json_result($sum_training_plans);
    }

    //GỌI AJAX CHI TIẾT CHI PHÍ
    public function ajaxDetailCost($plan_id, Request $request) {
        $type_course = $request->type_course;
        $sessionCheckTypeCost = session()->get('checkTypeCost') ? session()->get('checkTypeCost') : [];
        $sessionUnCheckTypeCost = session()->get('uncheckTypeCost') ? session()->get('uncheckTypeCost') : [];
        $detail_id = $request->detail_id;
        $get_training_plan_type_costs = TrainingPlan::select('type_costs')->where('id',$plan_id)->where('status',1)->first();
        $cost_of_types = [];

        if (!empty($get_training_plan_type_costs->type_costs) ) {
            $array_training_plan_type_costs = json_decode($get_training_plan_type_costs->type_costs);
            foreach($array_training_plan_type_costs as $key => $item) {
                $training_plan_detail_type_cost = TrainingPlanDetailTypeCost::where('training_plan_id',$plan_id)->where('training_plan_detail_id', $detail_id)->where('cost_id',$item->id)->first();

                if( $item->type_cost_id == $request->type_cost_id && empty($sessionCheckTypeCost) && $item->set_cost > 0 ) {
                    $training_cost = TrainingCost::find($item->id);
                    // dd(1);
                    if (!empty($sessionUnCheckTypeCost) && in_array($item->id, $sessionUnCheckTypeCost) ) {
                        $cost_of_types[] =  [$item->id, $training_cost->name, $item->set_cost, $item->type_cost_id, 0];
                    } else if (!empty($training_plan_detail_type_cost) && $training_plan_detail_type_cost->status == 1 ) {
                        $cost_of_types[] =  [$item->id, $training_cost->name, $item->set_cost, $item->type_cost_id, 1];
                    } else if (!empty($training_plan_detail_type_cost) && $training_plan_detail_type_cost->status == 0 ) {
                        $cost_of_types[] =  [$item->id, $training_cost->name, $item->set_cost, $item->type_cost_id, 0];
                    } else if ( (isset($request->check_isset_total) && !empty(array_intersect($request->check_isset_training_form, $item->training_form_id)) && in_array(2,$type_course)) ) {
                        $cost_of_types[] =  [$item->id, $training_cost->name, $item->set_cost, $item->type_cost_id, 1];
                    } 
                } else if ($item->type_cost_id == $request->type_cost_id && !empty($sessionCheckTypeCost) && $item->set_cost > 0) {
                    $training_cost = TrainingCost::find($item->id);
                    // dd(2);
                    if ( !empty($sessionUnCheckTypeCost) && in_array($item->id, $sessionUnCheckTypeCost) ) {
                        $cost_of_types[] =  [$item->id, $training_cost->name, $item->set_cost, $item->type_cost_id, 0];
                    } else if ( in_array($item->id, $sessionCheckTypeCost) ) {
                        $cost_of_types[] =  [$item->id, $training_cost->name, $item->set_cost, $item->type_cost_id, 1];
                    } else if (!empty($training_plan_detail_type_cost) && $training_plan_detail_type_cost->status == 1 ) {
                        $cost_of_types[] =  [$item->id, $training_cost->name, $item->set_cost, $item->type_cost_id, 1];
                    } else if ( !empty($training_plan_detail_type_cost) && $training_plan_detail_type_cost->status == 0 ) {
                        $cost_of_types[] =  [$item->id, $training_cost->name, $item->set_cost, $item->type_cost_id, 0];
                    } else if ( isset($request->check_isset_total) && !empty(array_intersect($request->check_isset_training_form, $item->training_form_id)) && in_array(2,$type_course) ) {
                        $cost_of_types[] =  [$item->id, $training_cost->name, $item->set_cost, $item->type_cost_id, 1];
                    } 
                }
            }
        }
        json_result($cost_of_types);
    }

    // TÍNH LẠI CHI PHÍ THEO CHI TIẾT
    public function ajaxTypeCostCalculate($plan_id, Request $request) {
        $detail_id = $request->detail_id;
        if($request->check_cost_checked == 1) {
            $sessionUncheck = session()->get('uncheckTypeCost');
            if (!empty($sessionUncheck) && ($key = array_search($request->cost_id, $sessionUncheck)) !== false) {
                unset($sessionUncheck[$key]);
                $sessionUncheck = array_values($sessionUncheck);
                session()->forget('uncheckTypeCost');
                session()->put('uncheckTypeCost', $sessionUncheck);
                session()->save();
            } else {
                session()->push('checkTypeCost', $request->cost_id);
                session()->save();
            }
        } else {
            $sessionCheck = session()->get('checkTypeCost');
            if (!empty($sessionCheck) && ($key = array_search($request->cost_id, $sessionCheck)) !== false) {
                unset($sessionCheck[$key]);
                $sessionCheck = array_values($sessionCheck);
                session()->forget('checkTypeCost');
                session()->put('checkTypeCost', $sessionCheck);
                session()->save();
            } else {
                session()->push('uncheckTypeCost', $request->cost_id);
                session()->save();
            }
        } 
        // dd(session()->get('uncheckTypeCost'));
        $money_type_cost_plan = $request->money_cost_plan ? str_replace(',', '', $request->money_cost_plan) : 0;

        $get_training_plan_type_costs = TrainingPlan::select('type_costs')->where('id',$plan_id)->where('status',1)->first();
        if (!empty($get_training_plan_type_costs->type_costs)) {
            $array_training_plan_type_costs = json_decode($get_training_plan_type_costs->type_costs);
            foreach($array_training_plan_type_costs as $key => $item) {
                if($item->type_cost_id == $request->type_cost_id && $item->id == $request->cost_id) {
                    $get_set_cost = $item->set_cost;                   
                } 
            }
        } 
        if($request->check_cost_checked == 1) {
            $sum_type_cost = $money_type_cost_plan + ($get_set_cost * $request->total);
        } else {
            $sum_type_cost = $money_type_cost_plan - ($get_set_cost * $request->total);
        } 
        json_result($sum_type_cost);
    }
}
