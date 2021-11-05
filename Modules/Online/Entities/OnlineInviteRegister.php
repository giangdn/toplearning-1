<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineInviteRegister
 *
 * @property int $id
 * @property int $course_id khóa học được mời ghi danh
 * @property int $unit_by đơn vị của khóa học được mời ghi danh
 * @property int $user_id nhân viên được mời
 * @property int $role_id vai trò người được mời
 * @property int $num_register sl nhân viên được phép khi danh trong khóa học
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister query()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister whereNumRegister($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister whereUnitBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineInviteRegister whereUserId($value)
 * @mixin \Eloquent
 */
class OnlineInviteRegister extends Model
{
    protected $table = 'el_online_invite_register';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'unit_by',
        'user_id',
        'role_id',
        'num_register',
    ];
}
