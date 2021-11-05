<?php

namespace Modules\Quiz\Entities;

use App\Traits\ChangeLogs;
use App\Traits\UserModify;
use HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Shetabit\Visitor\Traits\Visitable;
use Shetabit\Visitor\Traits\Visitor;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modules\Quiz\Entities\QuizUserSecondary
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string|null $dob
 * @property string|null $email
 * @property string|null $identity_card
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary whereIdentityCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuizUserSecondary whereUsername($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUserSecondary permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizUserSecondary role($roles, $guard = null)
 */
class QuizUserSecondary extends Authenticatable
{
    use Notifiable, HasRoles, ChangeLogs, UsersOnlineTrait, UserModify;
    protected $table = 'el_quiz_user_secondary';
    protected $fillable = [
        'code',
        'name',
        'username',
        'password',
        'email',
        'dob',
        'identity_card',
    ];

    protected $hidden = [
        'password',
    ];

    public static function getAttributeName() {
        return [
            'code' => 'Mã nhân viên',
            'name' => 'Tên nhân viên',
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'repassword' => 'Nhập lại mật khẩu',
            'email' => 'Email',
            'dob' => 'Ngày sinh',
            'identity_card' => 'Số CMND',
        ];
    }

    public static function getCompany($user_id = null)
    {
        $user_id = empty($user_id) ? \Auth::guard('secondary')->id() : $user_id;
        return \DB::table('el_quiz_user_secondary')
            ->join('el_unit_view','el_quiz_user_secondary.unit_by','=','el_unit_view.id')
            ->where('el_quiz_user_secondary.id',$user_id)
            ->value('el_unit_view.unit0_id');
    }
}
