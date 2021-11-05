<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\Online\Entities\OnlineRegister;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineRating;
use Modules\Offline\Entities\OfflineRating;
/**
 * App\CourseView
 *
 * @property int $id
 * @property int $course_id
 * @property int $course_type 1: online, 2: offline
 * @property string $code
 * @property string $name
 * @property int $auto 1: tự động duyệt, 0: duyệt tay
 * @property string|null $unit_id Mã Đơn vị tạo khóa học
 * @property int|null $moodlecourseid
 * @property int|null $in_plan Trong kế hoạch
 * @property int|null $training_form_id Mã hình thức đào tạo
 * @property string|null $training_form_name Hình thức đào tạo
 * @property int|null $plan_detail_id
 * @property string|null $description
 * @property int $isopen
 * @property string|null $tutorial
 * @property string|null $type_tutorial
 * @property int $status
 * @property string $start_date
 * @property string $end_date
 * @property string|null $register_deadline Hạn đăng ký
 * @property string|null $image
 * @property int $max_student
 * @property string|null $document
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int $training_program_id
 * @property string|null $training_program_name
 * @property int|null $level_subject_id
 * @property int $subject_id Mã chuyên đề
 * @property string $subject_name chuyên đề
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
 * @property int|null $num_lesson Bài học
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
 * @property int|null $is_limit_time giới hạn thời gian học
 * @property string|null $start_timeday
 * @property string|null $end_timeday
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereActionPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereActualAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCertCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCoefficient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCommitDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCostClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCourseAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCourseActionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCourseEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCourseEmployeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCourseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereEndTimeday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereExpireCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereHasCert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereHasChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereInPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereIsLimitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereIsopen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereLevelSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereLockCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereMaxGrades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereMaxStudent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereMinGrades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereMoodlecourseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereNumLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView wherePlanAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView wherePlanAppDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView wherePlanAppTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView wherePlanAppTemplateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView wherePlanDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereQuizName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereRegisterDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereSchedules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereStartTimeday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereSubjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTeacherTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTeacherTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTemplateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTitleJoinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTitleJoinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTitleRecommendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTitleRecommendName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingAreaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingFormName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingLocationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingObjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingPartnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingPartnerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingProgramName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTutorial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTypeTutorial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereViews($value)
 * @mixin \Eloquent
 * @property string|null $training_program_code Mã chủ đề
 * @property int $is_roadmap Khóa học trong tháp đào tạo
 * @property string|null $approved_step
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereApprovedStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereIsRoadmap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseView whereTrainingProgramCode($value)
 */
class CourseView extends Model
{
    protected $table = 'el_course_view';
    protected $fillable =[
        'course_id',
        'course_type',
        'code',
        'name',
        'auto',
        'moodlecourseid',
        'tutorial',
        'type_tutorial',
        'unit_id',
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
        'is_limit_time',
        'start_timeday',
        'end_timeday',
        'is_roadmap',
        'role_id',
        'approved_step',
        'training_partner_type',
        'training_unit_type',
    ];

    public function result($type)
    {
        if($type == 1) {
            return $this->belongsToMany('Modules\Online\Entities\OnlineRegister','el_online_result','course_id','register_id','course_id');
        } else {
            return $this->belongsToMany('Modules\Offline\Entities\OfflineRegister','el_offline_result','course_id','register_id','course_id');
        }
    }

    public function getStatus($type)
    {
        $result = $this->result($type)->wherePivot('user_id',auth()->id());
        $startDate = $this->start_date;
        $endDate = $this->end_date;
        if ($this->register($type)->exists()){
            if ($startDate > now()){
                $status = "Đã đăng ký";
            }else {
                if($endDate && $endDate < now()){
                    if ($result->exists()) {
                        $status = "Đã học (Đã có kết quả,Khóa đã kết thúc)";
                    } else
                        $status = "Đang học (Chưa có kết quả,Khóa đã kết thúc)";
                }elseif($endDate){
                    if ($result->exists()) {
                        $status = "Đã học (Đã có kết quả,Chưa kết thúc khóa)";
                    } else
                        $status = "Đang học (Chưa có kết quả,Chưa kết thúc khóa)";
                }else{
                    if ($result->exists()) {
                        $status = "Đã học (Đã có kết quả)";
                    } else
                        $status = "Đang học (Chưa có kết quả)";
                }
            }
        }else{
            $status = "Chưa đăng ký";
        }
        return $status;
    }

    public function bookmarked($type)
    {
        if ($type == 1) {
            return $this->hasOne('App\CourseBookmark','course_id','course_id')
                ->where('user_id','=',auth()->id())
                ->where('type',1);
        } else {
            return $this->hasOne('App\CourseBookmark','course_id','course_id')
                ->where('user_id','=',auth()->id())
                ->where('type',2);
        }
    }

    public function pointSetting($type)
    {
        if ($type == 1) {
            return $this->hasOne('Modules\Promotion\Entities\PromotionCourseSetting','course_id','course_id')->where('type','1');
        } else {
            return $this->hasOne('Modules\Promotion\Entities\PromotionCourseSetting','course_id','course_id')->where('type','2');
        }
    }

    public function register($type)
    {
        if ($type == 1) {
            return $this->hasMany('Modules\Online\Entities\OnlineRegister','course_id','course_id')->where('status', '1');
        } else {
            return $this->hasMany('Modules\Offline\Entities\OfflineRegister','course_id','course_id')->where('status', '1');
        }
    }

    public function countRatingStar($type) {
        if ($type == 1) {
            return OnlineRating::where('course_id', '=', $this->course_id)
            ->count();
        } else {
            return OfflineRating::where('course_id', '=', $this->course_id)
            ->count();
        }
    }

    public function avgRatingStar($type) {
        if ($type == 1) {
            $count = $this->countRatingStar($type);
            $total = OnlineRating::where('course_id', '=', $this->course_id)
                ->first(\DB::raw('SUM(num_star) AS total'))->total;
            return $count > 0 ? round($total / $count,1) : 0;
        } else {
            $count = $this->countRatingStar($type);
            $total = OfflineRating::where('course_id', '=', $this->course_id)
                ->first(\DB::raw('SUM(num_star) AS total'))->total;
            return $count > 0 ? round($total / $count,1) : 0;
        }
    }

    public function getStatusRegister($type) {
        $nowdate = date('Y-m-d H:i:s');
        if ($this->end_date && $this->end_date < $nowdate) {
            return 3;
        }

        $user_id = \Auth::id();
        if ($type == 1) {
            $registed = OnlineRegister::where('user_id', '=', $user_id)
            ->where('course_id', '=', $this->course_id)
            ->first();
        } else {
            $registed = OfflineRegister::where('user_id', '=', $user_id)
            ->where('course_id', '=', $this->course_id)
            ->first();
        }

        if($this->auto == 2) {
            return 4;
        } else if ($registed) {
            if ($registed->status == 1) {
                if ($this->start_date > $nowdate) {
                    return 7;
                }

                if ($this->start_date < $nowdate) {
                    if($this->end_date && $this->end_date > $nowdate) {
                        return 4;
                    }
                    return 4;
                }
            }

            if ($registed->status == 2) {
                return 5;
            }

            if ($registed->status == 0) {
                return 6;
            }
        }
        if (empty($this->register_deadline)){
            return 1;
        }else {
            if ($this->register_deadline > $nowdate) {
                return 1;
            }

            if ($this->register_deadline < $nowdate) {
                return 2;
            }
        }
        return 0;
    }
}
