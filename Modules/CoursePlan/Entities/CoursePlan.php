<?php

namespace Modules\CoursePlan\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\CoursePlan\Entities\CoursePlan
 *
 * @property int $id
 * @property int $course_type 1: online, 2:offline
 * @property string|null $code
 * @property string $name
 * @property int $auto 1: tự động duyệt, 0: duyệt tay
 * @property string|null $unit_id Đơn vị tạo khóa học
 * @property int|null $unit_type
 * @property int|null $moodlecourseid
 * @property int $isopen
 * @property string|null $image
 * @property string $start_date
 * @property string|null $end_date
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $category_id
 * @property string|null $description
 * @property int $training_program_id
 * @property int|null $level_subject_id
 * @property int $subject_id
 * @property int|null $plan_detail_id
 * @property int|null $in_plan Trong kế hoạch
 * @property int|null $training_form_id Hình thức đào tạo
 * @property string|null $register_deadline Hạn đăng ký
 * @property string|null $content
 * @property string|null $document
 * @property string|null $course_time Thời lượng
 * @property int|null $num_lesson Bài học
 * @property int $status
 * @property int $views
 * @property int $action_plan Đánh giá hiệu quả đào tạo
 * @property int|null $plan_app_template Mẫu Đánh giá hiệu quả đào tạo
 * @property int|null $plan_app_day Thời hạn đánh giá
 * @property int|null $cert_code
 * @property int|null $has_cert
 * @property int|null $rating Đánh giá sau khóa học
 * @property int|null $template_id Mẫu đánh giá
 * @property int|null $unit_by
 * @property int|null $max_student
 * @property int|null $training_location_id
 * @property string|null $training_unit
 * @property int|null $training_partner_type
 * @property int|null $training_unit_type
 * @property string|null $training_area_id
 * @property string|null $training_partner_id
 * @property int|null $teacher_id
 * @property int|null $commit
 * @property string|null $commit_date
 * @property float|null $coefficient
 * @property string|null $cost_class
 * @property int|null $quiz_id
 * @property int $status_convert
 * @property int|null $approved_by
 * @property string|null $time_approved
 * @property int|null $max_grades
 * @property int|null $min_grades
 * @property int|null $course_employee
 * @property int|null $course_action
 * @property string|null $title_join_id
 * @property string|null $title_recommend_id
 * @property string|null $training_object_id
 * @property int|null $teacher_type_id
 * @property string|null $training_type_id
 * @property int|null $is_limit_time
 * @property string|null $start_timeday
 * @property string|null $end_timeday
 * @property string|null $approved_step
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereActionPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereApprovedStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCertCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCoefficient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCommitDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCostClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCourseAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCourseEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCourseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereEndTimeday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereHasCert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereInPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereIsLimitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereIsopen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereLevelSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereMaxGrades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereMaxStudent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereMinGrades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereMoodlecourseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereNumLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan wherePlanAppDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan wherePlanAppTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan wherePlanDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereRegisterDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereStartTimeday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereStatusConvert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTeacherTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTimeApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTitleJoinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTitleRecommendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTrainingAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTrainingFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTrainingLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTrainingObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTrainingPartnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTrainingPartnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTrainingProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTrainingTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTrainingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereTrainingUnitType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereUnitType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CoursePlan whereViews($value)
 * @mixin \Eloquent
 */
class CoursePlan extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'el_course_plan';
    protected $fillable = [
        'course_type',
        'code',
        'name',
        'auto',
        'unit_id',
        'moodlecourseid',
        'isopen',
        'image',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'category_id',
        'description',
        'training_program_id',
        'level_subject_id',
        'subject_id',
        'plan_detail_id',
        'in_plan',
        'training_form_id',
        'register_deadline',
        'content',
        'document',
        'course_time',
        'num_lesson',
        'status',
        'views',
        'action_plan',
        'plan_app_template',
        'plan_app_day',
        'cert_code',
        'has_cert',
        'rating',
        'template_id',
        'unit_by',
        'max_student',
        'training_location_id',
        'training_unit',
        'training_area_id',
        'training_partner_id',
        'teacher_id',
        'commit',
        'commit_date',
        'coefficient',
        'cost_class',
        'quiz_id',
        'approved_by',
        'time_approved',
        'status_convert',
        'max_grades',
        'min_grades',
        'course_employee',
        'course_action',
        'title_join_id',
        'title_recommend_id',
        'training_object_id',
        'teacher_type_id',
        'training_type_id',
        'is_limit_time',
        'start_timeday',
        'end_timeday',
        'training_unit_type',
        'training_partner_type',
        'unit_type'
    ];
}
