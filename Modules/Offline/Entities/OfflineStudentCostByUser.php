<?php

namespace Modules\Offline\Entities;

use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineStudentCostByUser
 *
 * @property int $id
 * @property int $register_id
 * @property int $cost_id
 * @property int $cost
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCostByUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCostByUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCostByUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCostByUser whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCostByUser whereCostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCostByUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCostByUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCostByUser whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCostByUser whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCostByUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineStudentCostByUser extends Model
{
    use ChangeLogs;
    protected $table = 'el_offline_student_cost_by_user';
    protected $fillable = [
        'register_id',
        'course_id',
        'cost_id',
        'cost',
        'note',
        'manager_approved'
    ];
    protected $primaryKey = 'id';

    public static function getRegister($regid)
    {
        $query = self::query();
        $query->select(['b.*', 'c.firstname as profile_firstname', 'c.lastname as profile_lastname']);
        $query->from('el_offline_register AS b');
        $query->leftJoin('el_profile AS c', 'c.user_id', '=', 'b.user_id');
        $query->where('b.id', '=', $regid);
        return $query->first();
    }

    public static function getAttributeName() {
        return [
            'register_id' => 'Mã học viên',
            'course_id' => 'Khóa học',
            'cost_id' => 'Chi phí học viên',
            'cost' => 'Chi phí khác',
            'note' => 'Ghi chú',
        ];
    }

    public static function getTotalStudentCost($id, $course_id)
    {
        $total = OfflineStudentCostByUser::whereRegisterId($id)->where('course_id', $course_id)->sum('cost');

        return $total;
    }
}
