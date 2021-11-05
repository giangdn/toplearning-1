<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineInviteRegister
 *
 * @property int $id
 * @property int $course_id khóa học được mời ghi danh
 * @property int $unit_by đơn vị của khóa học được mời ghi danh
 * @property int $user_id nhân viên được mời
 * @property int $role_id vai trò người được mời
 * @property int $num_register sl nhân viên được phép khi danh trong khóa học
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister whereNumRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineInviteRegister whereUserId($value)
 * @mixin \Eloquent
 */
class OfflineInviteRegister extends Model
{
    protected $table = 'el_offline_invite_register';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'unit_by',
        'user_id',
        'role_id',
        'num_register',
    ];
}
