<?php

namespace Modules\TrainingPlan\Imports;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingProgram;
use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\ToModel;
use Modules\TrainingPlan\Entities\TrainingPlanDetail;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Categories\TrainingObject;
use App\Models\Categories\Unit;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TrainingCost;
use Modules\TrainingPlan\Entities\TrainingPlan;
use App\TypeCost;
use Modules\TrainingPlan\Entities\TrainingPlanDetailTypeCost;

class TrainingPlanDetailImport implements ToModel, WithStartRow
{

    public $plan_id;
    public $errors;
    protected $training_cost = 17;

    public function __construct($plan_id)
    {
        $this->plan_id = $plan_id;
        $this->errors = [];
    }

    public function model(array $row) {
        $error = false;

        $check_subject = Subject::where('code', trim($row[1]))->first();
        if (empty($row[1])) {
            $this->errors[] = 'Mã Khóa học dòng: <b>'. $row[0] .'</b> không được trống';
            $error = true;
        } else if (isset($row[1]) && empty($check_subject)){
            $this->errors[] = 'Mã Khóa học dòng: <b>'. $row[0] .'</b> không đúng';
            $error = true;
        }

        $training_program = TrainingProgram::where('code', trim($row[2]))->first();
        if (empty($row[2])) {
            $this->errors[] = 'Mã chương trình đào tạo dòng: <b>'. $row[0] .'</b> không được trống';
            $error = true;
        } else if (isset($row[2]) && empty($training_program)) {
            $this->errors[] = 'Mã chương trình đào tạo dòng: <b>'. $row[0] .'</b> không đúng';
            $error = true;
        }

        if(empty($row[3])) {
            $this->errors[] = 'Hình thức đào tạo dòng: <b>'. $row[0] .'</b> không được trống';
            $error = true;
        } else {
            $course_type = explode(',', $row[3]);
        }

        if (empty($row[4])) {
            $this->errors[] = 'Loại hình đào tạo dòng: <b>'. $row[0] .'</b> không được trống';
            $error = true;
        } else {
            $get_training_forms = explode(',',$row[4]);
            foreach($get_training_forms as $get_training_form) {
                $check_training_form = TrainingForm::where('code', $get_training_form)->first();
                if (empty($check_training_form)){
                    $this->errors[] = 'Loại hình đào tạo dòng: <b>'. $row[0] .'</b> không đúng';
                    $error = true;
                }
                $training_forms[] = $check_training_form->id;
            }
        }
        $training_partners = [];
        $training_partner_type = 0;
        if( (isset($row[5]) && !isset($row[6])) ) {
            $get_units = explode(',',$row[5]);
            foreach($get_units as $get_unit) {
                $check_unit = Unit::where('code', $get_unit)->first();
                if (empty($check_unit)){
                    $this->errors[] = 'Đơn vị dòng: <b>'. $row[0] .'</b> không đúng';
                    $error = true;
                }
                $training_partners[] = $check_unit->id;
            }
            $training_partner_type = 1;
        }

        if( (!isset($row[5]) && isset($row[6])) ) {
            $get_training_partners = explode(',',$row[6]);
            foreach($get_training_partners as $get_training_partner) {
                $check_training_partner = TrainingPartner::where('code', $get_training_partner)->first();
                if (empty($check_training_partner)){
                    $this->errors[] = 'Đơn vị dòng: <b>'. $row[0] .'</b> không đúng';
                    $error = true;
                }
                $training_partners[] = $check_training_partner->id;
            }
        }

        $training_partners_responsable = [];
        $responsable_type = 0;
        if( isset($row[7]) && !isset($row[8]) ) {
            $get_units_responsable = explode(',',$row[7]);
            foreach($get_units_responsable as $get_unit_responsable) {
                $check_unit_responsable = Unit::where('code', $get_unit_responsable)->first();
                if (empty($check_unit_responsable)){
                    $this->errors[] = 'Đơn vị dòng: <b>'. $row[0] .'</b> không đúng';
                    $error = true;
                }
                $training_partners_responsable[] = $check_unit_responsable->id;
            }
            $responsable_type = 1;
        }

        if( !isset($row[7]) && isset($row[8]) ) {
            $get_training_partners_responsable = explode(',',$row[8]);
            foreach($get_training_partners_responsable as $get_training_partner_responsable) {
                $check_training_partner_responsable = TrainingPartner::where('code', $get_training_partner_responsable)->first();
                if (empty($check_training_partner_responsable)){
                    $this->errors[] = 'Đơn vị dòng: <b>'. $row[0] .'</b> không đúng';
                    $error = true;
                }
                $training_partners_responsable[] = $check_training_partner_responsable->id;
            }
        }

        $periods = (int) $row[9];
        if(!isset($periods)) {
            $this->errors[] = 'Thời lượng đào tạo dòng: <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        $quarter1 = (int) $row[10];
        $quarter2 = (int) $row[11];
        $quarter3 = (int) $row[12];
        $quarter4 = (int) $row[13];

        $total_course = $quarter1 + $quarter2 + $quarter3 + $quarter4;

        $total_student = (int) $row[14];
        if(!isset($total_student)) {
            $this->errors[] = 'Số lượng học viên dòng: <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        $exis_training_CBNV = (int) $row[15];
        $recruit_training_CBNV = (int) $row[16];

        if( isset($row[17]) ) {
            $get_training_objects = explode(',',$row[17]);
            foreach($get_training_objects as $get_training_object) {
                $check_get_training_object = TrainingObject::where('code', $get_training_object)->first();
                if (empty($check_get_training_object)){
                    $this->errors[] = 'Đơn vị dòng: <b>'. $row[0] .'</b> không đúng';
                    $error = true;
                }
                $training_object[] = $check_get_training_object->id;
            }
        }
        
        if($error) {
            return null;
        }

        $get_training_costs = TrainingCost::get();
        foreach($get_training_costs as $get_training_cost) {
            $this->training_cost += 1;
            $training_costs[$get_training_cost->id] = $row[$this->training_cost];
        }
        $get_training_plan_type_costs = TrainingPlan::find($this->plan_id);
        $sum = [];
        
        if (!empty($get_training_plan_type_costs->type_costs)) {
            $array_training_plan_type_costs = json_decode($get_training_plan_type_costs->type_costs);
            foreach($array_training_plan_type_costs as $key => $item) {
                if( !empty(array_intersect($training_forms, $item->training_form_id)) && in_array(2,$course_type) && $training_costs[$item->id] == 1 ){
                    if (isset($sum[$item->type_cost_id])) {
                        $sum[$item->type_cost_id] += $item->set_cost;
                    } else {
                        $sum[$item->type_cost_id] = $item->set_cost;
                    }
                } 
            }
        } 
        
        $sum_training_plans = $sum;
        $total_type_cost = array_sum($sum_training_plans);
        $get_type_costs = TypeCost::get();
        foreach($get_type_costs as $key => $get_type_cost) {
            $object = new \StdClass();
            $object->id = $get_type_cost->id;
            $object->money_cost = isset($sum_training_plans[$get_type_cost->id]) ? ($sum_training_plans[$get_type_cost->id] * $total_course) : 0;
            $all_type_costs[] = $object;
        }
        $json_all_type_costs = json_encode($all_type_costs);

        $model = TrainingPlanDetail::firstOrNew(['plan_id' => $this->plan_id, 'training_program_id' => $training_program->id, 'subject_id' => $check_subject->id]);
        $model->plan_id = $this->plan_id;
        $model->training_program_id = $training_program->id;
        $model->subject_id = $check_subject->id;
        $model->course_type = $row[3];
        $model->training_form_id = is_array($training_forms) ? implode(',', $training_forms) : null;
        $model->training_partner = !empty($training_partners) && is_array($training_partners) ? implode(',', $training_partners) : null;
        $model->training_partner_type = $training_partner_type;
        $model->periods = $periods;
        $model->quarter1 = $quarter1;
        $model->quarter2 = $quarter2;
        $model->quarter3 = $quarter3;
        $model->quarter4 = $quarter4;
        $model->responsable = !empty($training_partners_responsable) && is_array($training_partners_responsable) ? implode(',', $training_partners_responsable) : null;
        $model->responsable_type = $responsable_type;
        $model->total_course = $total_course;
        $model->total_student = $total_student;
        $model->type_costs = $json_all_type_costs;
        $model->total_type_cost = ($total_type_cost * $total_course);
        $model->exis_training_CBNV = $exis_training_CBNV;
        $model->recruit_training_CBNV = $recruit_training_CBNV;
        $model->training_object_id = isset($training_object) && is_array($training_object) ? implode(',', $training_object) : null;
        $model->save();

        foreach($training_costs as $key => $training_cost) {
            if(empty($training_cost)) {
                $query = TrainingPlanDetailTypeCost::firstOrNew(['training_plan_id'=> $this->plan_id, 'cost_id'=> $key, 'training_plan_detail_id' => $model->id]);
                $query->status = 0;
                $query->training_plan_id = $this->plan_id;
                $query->cost_id = $key;
                $query->training_plan_detail_id = $model->id;
                $query->save();
            }
        }
        
    }

    public function startRow(): int
    {
        return 3;
    }

}
