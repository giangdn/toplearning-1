<?php

namespace App;

use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * App\PlanApp
 *
 * @property int $id
 * @property int $plan_app_id
 * @property int $user_id
 * @property int $course_id
 * @property int $course_type
 * @property string|null $suggest_self
 * @property string|null $suggest_manager
 * @property int|null $evaluation_self
 * @property string|null $evaluation_manager
 * @property string|null $approved_date
 * @property string|null $evaluation_date
 * @property string|null $start_date
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereApprovedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereEvaluationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereEvaluationManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereEvaluationSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp wherePlanAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereSuggestManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereSuggestSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PlanApp whereUserId($value)
 * @mixin \Eloquent
 */
class PlanApp extends BaseModel
{
    use ChangeLogs;

    protected $table = 'el_plan_app';
    protected $fillable = [
        'plan_app_id',
        'user_id',
        'course_id',
        'course_type',
        'suggest_self',
        'suggest_manager',
        'evaluation_self',
        'evaluation_manager',
        'approved_date',
        'evaluation_date',
        'start_date',
        'status',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'plan_app_id' => 'Mã Đánh giá hiệu quả đào tạo',
            'user_id' => 'Mã user id',
            'course_id' => 'Mã khóa học',
            'course_type' => 'Loại khóa học',
            'suggest_self' => 'Đề xuất học viên',
            'suggest_manager' => 'Đề xuất trưởng đơn vị',
            'evaluation_self' => 'Đánh giá học viên',
            'evaluation_manager' => 'Đánh giá trưởng đơn vị',
            'approved_date' => 'Ngày TĐV duyệt',
            'evaluation_date' => 'Ngày nhân viên tự đánh giá',
            'start_date' => 'Ngày bắt đầu đánh giá',
            'status' => 'Trạng thái'
        ];
    }
}
