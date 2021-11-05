<?php

namespace Modules\Offline\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineCourseView
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $unit_id Mã Đơn vị tạo khóa học
 * @property int|null $in_plan Trong kế hoạch
 * @property int|null $training_form_id Mã hình thức đào tạo
 * @property string|null $training_form_name Hình thức đào tạo
 * @property int|null $plan_detail_id
 * @property string|null $description
 * @property int $isopen
 * @property int $status
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $register_deadline
 * @property string|null $image
 * @property int $max_student
 * @property string|null $document
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int $training_program_id
 * @property string|null $training_program_code
 * @property string|null $training_program_name chủ đề
 * @property int|null $level_subject_id
 * @property int $subject_id Mã chuyên đề
 * @property string|null $subject_code chuyên đề
 * @property string|null $subject_name chuyên đề
 * @property int|null $training_location_id Mã địa điểm đào tạo
 * @property string|null $training_location_name địa điểm đào tạo
 * @property string|null $training_unit Đơn vị đào tạo
 * @property int|null $training_area_id Mã khu vực đào tạo
 * @property string|null $training_area_name Khu vực đào tạo
 * @property int|null $training_partner_id Mã đối tác
 * @property string|null $training_partner_name Đối tác
 * @property string|null $content
 * @property int $views
 * @property int|null $category_id
 * @property string|null $course_time Thời lượng
 * @property string|null $course_time_unit
 * @property int|null $num_lesson
 * @property int $action_plan Đánh giá hiệu quả đào tạo
 * @property int|null $plan_app_template Id Mẫu Đánh giá hiệu quả đào tạo
 * @property string|null $plan_app_template_name Mẫu Đánh giá hiệu quả đào tạo
 * @property int|null $plan_app_day Thời hạn đánh giá
 * @property int|null $cert_code
 * @property int|null $has_cert
 * @property int|null $teacher_id Giảng viên
 * @property int|null $rating Đánh giá sau khóa học
 * @property int|null $template_id Mẫu đánh giá
 * @property string|null $template_name Mẫu đánh giá
 * @property int|null $commit Cam kết đào tạo
 * @property string|null $commit_date Ngày bắt đầu tính cam kết
 * @property float|null $coefficient Hệ số k
 * @property string|null $cost_class Chi phí tổ chức
 * @property int|null $quiz_id Mã Kỳ thi
 * @property string|null $quiz_name Tên Kỳ thi
 * @property int|null $unit_by
 * @property int|null $max_grades
 * @property int|null $min_grades
 * @property int|null $course_employee Khóa học dành cho
 * @property string|null $course_employee_name tên khóa học dành cho
 * @property int|null $course_action Mã Khóa học thực hiện
 * @property string|null $course_action_name Khóa học thực hiện
 * @property int|null $title_join_id Mã chức danh tham gia
 * @property string|null $title_join_name Chức danh tham gia
 * @property int|null $title_recommend_id Mã chức danh khuyến khích
 * @property string|null $title_recommend_name Tên chức danh khuyến khích
 * @property int|null $training_object_id Mã nhóm đối tượng tham gia
 * @property string|null $training_object_name Nhóm đối tượng tham gia
 * @property int|null $teacher_type_id Mã loại giảng viên
 * @property string|null $teacher_type_name loại giảng viên
 * @property int|null $training_type_id Mã hình thức đào tạo
 * @property string|null $training_type_name Tên hình thức đào tạo
 * @property int $lock_course
 * @property int $has_change ghi nhận thay đổi
 * @property string|null $schedules Buổi học
 * @property int|null $plan_amount Chi phí tạm tính của khóa học
 * @property int|null $actual_amount chi phí thực chi của khóa học
 * @property int $expire_commit Khóa có cam kết đã hết hạn ghi nhận 1 để khi chạy cron loại bỏ những khóa này
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereActionPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereActualAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCertCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCoefficient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCommitDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCostClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCourseAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCourseActionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCourseEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCourseEmployeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCourseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereExpireCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereHasCert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereHasChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereInPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereIsopen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereLevelSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereLockCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereMaxGrades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereMaxStudent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereMinGrades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereNumLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView wherePlanAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView wherePlanAppDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView wherePlanAppTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView wherePlanAppTemplateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView wherePlanDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereQuizName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereRegisterDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereSchedules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereSubjectCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereSubjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTeacherTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTeacherTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTemplateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTitleJoinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTitleJoinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTitleRecommendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTitleRecommendName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingAreaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingFormName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingLocationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingObjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingPartnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingPartnerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingProgramCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingProgramName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereTrainingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereViews($value)
 * @mixin \Eloquent
 * @property int|null $is_roadmap
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereIsRoadmap($value)
 * @property string|null $approved_step
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseView whereApprovedStep($value)
 */
class OfflineCourseView extends BaseModel
{
    protected $table='el_offline_course_view';
    protected $fillable = [
        'id',
        'code',
        'name',
        'unit_id',
        'unit_name',
        'in_plan',
        'training_form_id',
        'training_form_name',
        'plan_detail_id',
        'description',
        'isopen',
        'status',
        'start_date',
        'end_date',
        'register_deadline',
        'image',
        'max_student',
        'document',
        'created_by',
        'updated_by',
        'training_program_id',
        'training_program_code',
        'training_program_name',
        'level_subject_id',
        'subject_id',
        'subject_code',
        'subject_name',
        'training_location_id',
        'training_location_name',
        'training_unit',
        'training_area_id',
        'training_area_name',
        'training_partner_id',
        'training_partner_name',
        'content',
        'views',
        'category_id',
        'course_time',
        'course_time_unit',
        'num_lesson',
        'action_plan',
        'plan_app_template',
        'plan_app_template_name',
        'plan_app_day',
        'cert_code',
        'has_cert',
        'teacher_id',
        'rating',
        'template_id',
        'template_name',
        'commit',
        'commit_date',
        'coefficient',
        'cost_class',
        'quiz_id',
        'quiz_name',
        'unit_by',
        'max_grades',
        'min_grades',
        'course_employee',
        'course_employee_name',
        'course_action',
        'course_action_name',
        'title_join_id',
        'title_join_name',
        'title_recommend_id',
        'title_recommend_name',
        'training_object_id',
        'training_object_name',
        'teacher_type_id',
        'teacher_type_name',
        'training_type_id',
        'training_type_name',
        'lock_course',
        'has_change',
        'schedules',
        'plan_amount',
        'actual_amount',
        'expire_commit',
        'is_roadmap',
        'approved_step',
        'training_partner_type',
        'training_unit_type',
    ];
}
