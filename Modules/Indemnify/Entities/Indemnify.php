<?php

namespace Modules\Indemnify\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Indemnify\Entities\Indemnify
 *
 * @property int $id
 * @property int $user_id mã user id
 * @property int $course_id mã khóa học
 * @property int|null $commit_date Tháng cam kết
 * @property int|null $date_diff Số tháng còn lại
 * @property string|null $commit_amount Số tiền cam kết
 * @property string|null $exemption_amount Số tiền miễn giảm
 * @property string|null $cost_student Chi phí học viên
 * @property string|null $course_cost Chi phí đào tạo
 * @property int|null $compensated Đã được bồi thường
 * @property string|null $cost_indemnify Chi phí bồi hoàn
 * @property float|null $coefficient hệ số K
 * @property string|null $contract Số hợp đồng cam kết
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify query()
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereCoefficient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereCommitAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereCommitDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereCompensated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereCostIndemnify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereCostStudent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereCourseCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereDateDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereExemptionAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indemnify whereUserId($value)
 * @mixin \Eloquent
 */
class Indemnify extends BaseModel
{
    protected $table = 'el_indemnify';
    protected $fillable = [
        'user_id',
        'course_id',
        'commit_date',
        'cost_class',
        'cost_student',
        'commit_amount',
        'created_at',
        'updated_at',
        'cost_indemnify',
        'course_cost',
        'day_off',
        'exemption_amount',
        'coefficient',
        'calculator',
        'compensated'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'user_id' => 'Mã user id',
            'course_id' => trans('lacourse.course_code'),
            'commit_date' => 'Ngày cam kết',
            'cost_class' => 'Chi phí tổ chức',
            'cost_student' => 'Chi phí học viên',
            'created_at' => 'Ngày tạo',
        ];
    }

    public static function checkIndemnify($user_id){
        $total = 0;

        $query = self::query();
        $query->where('user_id', '=', $user_id);
        $rows = $query->get();

        foreach ($rows as $row){
            if ($row->compensated == 1){
                $total++;
            }
        }

        if ($total == $rows->count()){
            return true;
        }
        return false;
    }

    public static function checkExists($user_id, $course_id){
        $query = self::query();
        $query->where('course_id', '=', $course_id);
        $query->where('user_id', '=', $user_id);
        return $query->first();
    }

    public static function getCommitAmount($user_id, $course_id){
        $query = self::query();
        $query->where('course_id', '=', $course_id);
        $query->where('user_id', '=', $user_id);

        return $query->first();
    }

    public static function sumCommitAmount($user_id, $course_id){
        $total = 0;

        $query = self::query();
        $query->where('course_id', '=', $course_id);
        $query->where('user_id', '=', $user_id);
        $rows = $query->get();

        foreach ($rows as $row){
            $total += $row->commit_amount - $row->exemption_amount;
        }

        return $total;
    }

    public static function sumCostIndemnify($user_id){
        $total = 0;

        $query = self::query();
        $query->where('user_id', '=', $user_id);
        $rows = $query->get();

        foreach ($rows as $row){
            $total += $row->cost_indemnify;
        }

        return $total;
    }

    public static function updateCompensated($user_id,$compensated)
    {
        Indemnify::where(['user_id'=>$user_id])->update(['compensated'=>$compensated]);
    }
}
