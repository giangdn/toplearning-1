<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseView
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $auto 1: tự động duyệt, 0: duyệt tay
 * @property string|null $unit_id Mã đơn vị tạo khóa học
 * @property int|null $moodlecourseid
 * @property int $isopen
 * @property string|null $tutorial
 * @property string|null $type_tutorial
 * @property string|null $image
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $category_id
 * @property string|null $description
 * @property int $training_program_id chủ đề
 * @property string|null $training_program_code Chủ đề
 * @property string|null $training_program_name Chủ đề
 * @property int|null $level_subject_id
 * @property int $subject_id
 * @property string|null $subject_code chuyên đề
 * @property string|null $subject_name chuyên đề
 * @property int|null $plan_detail_id
 * @property int|null $in_plan Trong kế hoạch
 * @property int|null $training_form_id Hình thức đào tạo
 * @property string|null $training_form_name Hình thức đào tạo
 * @property string|null $register_deadline Hạn đăng ký
 * @property string|null $content
 * @property string|null $document
 * @property string|null $course_time Thời lượng
 * @property int|null $num_lesson Bài học
 * @property int $status
 * @property int $views
 * @property int $action_plan Đánh giá hiệu quả đào tạo
 * @property int|null $plan_app_template Mã mẫu Đánh giá hiệu quả đào tạo
 * @property string|null $plan_app_template_name Mẫu Đánh giá hiệu quả đào tạo
 * @property int|null $plan_app_day Thời hạn đánh giá
 * @property int|null $cert_code
 * @property int|null $has_cert
 * @property int|null $rating Đánh giá sau khóa học
 * @property int|null $template_id mã mẫu đánh giá
 * @property string|null $template_name Mẫu đánh giá
 * @property int|null $unit_by
 * @property int|null $max_grades
 * @property int|null $min_grades
 * @property int|null $title_join_id mã chức danh tham gia
 * @property string|null $title_join_name chức danh tham gia
 * @property int|null $title_recommend_id Chức danh khuyến khích
 * @property string|null $title_recommend_name Chức danh khuyến khích
 * @property int|null $training_object_id Đối tượng tham gia
 * @property string|null $training_object_name Đối tượng tham gia
 * @property int|null $is_limit_time giới hạn thời gian học
 * @property string|null $start_timeday
 * @property string|null $end_timeday
 * @property int $lock_course
 * @property string|null $rating_end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView query()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereActionPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereAuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereCertCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereCourseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereEndTimeday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereHasCert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereInPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereIsLimitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereIsopen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereLevelSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereLockCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereMaxGrades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereMinGrades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereMoodlecourseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereNumLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView wherePlanAppDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView wherePlanAppTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView wherePlanAppTemplateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView wherePlanDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereRatingEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereRegisterDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereStartTimeday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereSubjectCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereSubjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTemplateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTitleJoinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTitleJoinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTitleRecommendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTitleRecommendName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTrainingFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTrainingFormName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTrainingObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTrainingObjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTrainingProgramCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTrainingProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTrainingProgramName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTutorial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereTypeTutorial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseView whereViews($value)
 * @mixin \Eloquent
 */
class OnlineCourseView extends Model
{
    protected $table = 'el_online_course_view';
    protected $fillable = [
        'id',
        'code',
        'name',
        'auto',
        'unit_id',
        'moodlecourseid',
        'isopen',
        'tutorial',
        'type_tutorial',
        'image',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'category_id',
        'description',
        'training_program_id',
        'training_program_code',
        'training_program_name',
        'level_subject_id',
        'subject_id',
        'subject_code',
        'subject_name',
        'plan_detail_id',
        'in_plan',
        'training_form_id',
        'training_form_name',
        'register_deadline',
        'content',
        'document',
        'num_lesson',
        'status',
        'views',
        'action_plan',
        'plan_app_template',
        'plan_app_template_name',
        'plan_app_day',
        'cert_code',
        'has_cert',
        'rating',
        'template_id',
        'template_name',
        'unit_by',
        'max_grades',
        'min_grades',
        'title_join_id',
        'title_join_name',
        'title_recommend_id',
        'title_recommend_name',
        'training_object_id',
        'training_object_name',
        'is_limit_time',
        'start_timeday',
        'end_timeday',
        'is_roadmap',
        'lock_course',
        'approved_step',
    ];
}
