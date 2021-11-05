<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\User\Entities\ManagerLevel
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_manager_id
 * @property int $level
 * @property string $start_date
 * @property string|null $end_date
 * @property int $approve
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereApprove($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUserManagerId($value)
 * @mixin \Eloquent
 */
class ManagerLevel extends Model
{
    protected $table = 'el_profile_manager';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'user_manager_id',
        'start_date',
        'end_date',
        'level',
        'status',
        'approve'
    ];

    public static function getAttributeName() {
        return [
            'user_id' => 'Nhân viên',
            'user_manager_id' => 'Quản lý',
            'start_date' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc',
        ];
    }
}
