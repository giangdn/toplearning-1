<?php

namespace Modules\TrainingPlan\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingPlan\Entities\TrainingPlanDetail
 *
 * @property int $id
 * @property int $plan_id
 * @property int $training_program_id
 * @property int|null $level_subject_id
 * @property int $subject_id
 * @property int $course_type Hình thức đào tạo: 1:Trực tuyến, 2:Tập trung, 3:Tự học
 * @property int|null $training_form_id Loại hình đào tạo
 * @property string|null $training_partner Đơn vị tổ chức
 * @property int $periods thời lượng đào tạo
 * @property int $t1
 * @property int $t2
 * @property int $t3
 * @property int $t4
 * @property int $t5
 * @property int $t6
 * @property int $t7
 * @property int $t8
 * @property int $t9
 * @property int $t10
 * @property int $t11
 * @property int $t12
 * @property string|null $responsable Chịu trách nhiệm tổ chức
 * @property int $total_course Số lượng khóa học
 * @property int $total_student Số lượng học viên
 * @property int $student_cost Chi phí học viên
 * @property int $plan_cost Chi phí dự kiến
 * @property int $training_cost_allocated Chi phí đào tạo đã phân bổ
 * @property int $training_costs_reimbursed Chi phí đào tạo phải bồi hoàn đến hiện tại
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property int $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereLevelSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail wherePeriods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail wherePlanCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereStudentCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereT9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereTotalCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereTotalStudent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereTrainingCostAllocated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereTrainingCostsReimbursed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereTrainingFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereTrainingPartner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereTrainingProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingPlanDetail whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class TrainingPlanDetail extends BaseModel
{
    protected $table = 'el_training_plan_detail';
    protected $fillable = [
        'plan_id',
        'subject_id',
        'training_program_id',
        'level_subject_id',
        'training_form_id',
        'note',
        'periods',
        'quarter1',
        'quarter2',
        'quarter3',
        'quarter4',
        'course_type',
        'total_student',
        'total_course',
        'training_partner',
        'training_partner_type',
        'responsable',
        'responsable_type',
        'training_cost_allocated',
        'training_costs_reimbursed',
        'exis_training_CBNV',
        'recruit_training_CBNV',
        'training_object_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'plan_id' => 'Kế hoạch đào tạo',
            'subject_id' => 'Học phần',
            'training_program_id' => 'Chương trình đào tạo',
            'note' => 'Ghi chú',
            'periods' => 'Thời lượng đào tạo',
            'quarter1' => 'Qúy 1',
            'quarter1' => 'Qúy 2',
            'quarter1' => 'Qúy 3',
            'quarter1' => 'Qúy 4',
            'course_type' => 'Hình thức đào tạo',
            'total_student' => 'Số học viên',
            'total_course' => 'Số khóa học',
            'training_partner' => 'Đơn vị tổ chức',
            'responsable' => 'Chịu trách nhiệm tổ chức',
        ];
    }
}
