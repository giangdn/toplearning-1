<?php

namespace App;

use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use App\ProfileView;

/**
 * App\Profile
 *
 * @property int $id
 * @property string $code
 * @property int $user_id
 * @property string $firstname
 * @property string $lastname
 * @property string|null $dob
 * @property string|null $address
 * @property string|null $email
 * @property string|null $identity_card
 * @property string|null $date_range
 * @property string|null $issued_by
 * @property int $gender
 * @property string|null $phone
 * @property string|null $contract_signing_date
 * @property string|null $effective_date
 * @property string|null $expiration_date
 * @property string|null $join_company
 * @property string|null $expbank
 * @property string|null $title_code
 * @property string|null $unit_code
 * @property string|null $certificate_code
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereCertificateCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereContractSigningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereDateRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereExpbank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereIdentityCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereIssuedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereJoinCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereTitleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $date_off
 * @property string|null $area_code
 * @property string|null $avatar
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereAreaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereDateOff($value)
 * @property string|null $level
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Profile inUnit($unit)
 * @property-read \App\User $user
 * @property-read \App\Models\Categories\Titles|null $titles
 * @property-read \App\Models\Categories\Unit|null $unit
 * @property int|null $role
 * @method static \Illuminate\Database\Eloquent\Builder|Profile view($alias = '')
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereRole($value)
 * @property string|null $id_code
 * @property string|null $referer
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereIdCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereReferer($value)
 * @property int $type_user
 * @property-read mixed $full_name
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereTypeUser($value)
 * @property int|null $position_id
 * @property int|null $unit_id
 * @property string|null $like_new bài viết đã thích
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereLikeNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUnitId($value)
 * @property int|null $title_id
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereTitleId($value)
 */
class Profile extends CacheModel
{
    use ChangeLogs;
    protected $table = 'el_profile';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'code',
        'user_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'dob',
        'title_code',
        'unit_code',
        'area_code',
        'address',
        'gender',
        'identity_card',
        'date_range',
        'issued_by',
        'contract_signing_date',
        'effective_date',
        'expiration_date',
        'join_company',
        'status',
        'expbank',
        'avatar',
        'certificate_code',
        'level',
        'id_code',
        'date_off',
        'referer',
        'unit_id',
        'position_id',
        'title_id',
        'date_title_appointment',
        'end_date_title_appointment',
        'marriage'
    ];
    //    protected $primaryKey = 'user_id';

    public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }

    public function titles()
    {
        return $this->belongsTo('App\Models\Categories\Titles', 'title_code', 'code');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Categories\Unit', 'unit_code', 'code');
    }

    public function getAvatar()
    {
        if (empty($this->avatar)) {
            return asset('/images/user_tt.png');
        } else {
            $fileExists =  upload_file('profile/' . $this->avatar);
            if ($fileExists)
                return $fileExists;
            else
                return $this->avatar;
        }
    }

    public function scopeInUnit($query, $unit)
    {
        if (is_array($unit)) {
            return $query->whereIn('unit_code', function (Builder $builder) use ($unit) {
                $builder->select(['code'])
                    ->from('el_unit')
                    ->whereIn('id', $unit);
            });
        }

        return $query->whereIn('unit_code', function (Builder $builder) use ($unit) {
            $builder->select(['code'])
                ->from('el_unit')
                ->where('id', '=', $unit);
        });
    }

    public function getFullName()
    {
        return $this->id ? $this->lastname . ' ' . $this->firstname : '';
    }

    public function scopeView($query, $alias = '')
    {
        if (Auth::id() == 2) {
            return $query;
        }

        $user = Profile::find(Auth::id());
        $alias = $alias ? $alias . "." : $alias;
        $unit_code = $user->unit_code;
        $unit_id = Unit::query()->where('code', '=', $unit_code)->value('id');

        $role_id = $user->roles()->value('id');
        $level = Unit::query()->where('id', '=', $unit_id)->value('level');
        $permission_id = \Spatie\Permission\Models\Permission::findByName('user')->id;
        $type_view = \DB::query()
            ->select(['a.permission_type_id', 'b.name', 'b.type'])
            ->from('el_role_permission_type as a')
            ->join('el_permission_type as b', 'a.permission_type_id', '=', 'b.id')
            ->where('a.role_id', '=', $role_id)
            ->where('a.permission_id', '=', $permission_id)
            ->first();

        $permission_type_id = $type_view->permission_type_id;

        if ($type_view->type == 1) { // nhóm quyền hệ thống
            if ($type_view->name == 'All')
                return $query;
            elseif ($type_view->name == 'Global')
                return $query->where($alias . 'company', '=', $user->company);
            elseif ($type_view->name == 'Group-Child')
                return $query->whereIn('d.id', function ($subquery) use ($level, $unit_id) {
                    $subquery->select(['id'])
                        ->from('el_unit')
                        ->where(\DB::raw('JSON_VALUE(hierarchy,\'$."' . $level . '"\')'), '=', $unit_id);
                });
            elseif ($type_view->name == 'Group')
                return $query->where('d.id', '=', $unit_id);
            elseif ($type_view->name == 'owner')
                return $query->where($alias . 'created_by', '=', $user->user_id);
        } else { // nhóm quyền tùy chỉnh
            return $query->whereIn('d.id', function ($subquery) use ($permission_type_id) {
                $subquery->select(['unit_id'])
                    ->from('el_permission_type_unit')
                    ->where('permission_type_id', '=', $permission_type_id);
            })->orWhere('d.id', '=', $unit_id);
        }
        return $query;
    }

    public static function countProfile()
    {
        return Profile::where('status', '!=', 0)
            ->where('user_id', '>', 2)
            ->count(['id']);
    }

    public static function generateShuffle()
    {
        $id_code = shuffle_refer();
        $exists = Profile::where('id_code', '=', $id_code)->whereNotNull('id_code')->exists();
        if ($exists) {
            self::generateShuffle();
        }
        return $id_code;
    }

    public static function validRefer($referer)
    {
        $user_id = Auth::id();
        return Profile::where('id_code', '=', $referer)->where('user_id', '!=', $user_id)->exists();
    }

    public static function getAttributeName()
    {
        return [
            'username' => data_locale('Tên đăng nhập', 'Username'),
            'password' => data_locale('Mật khẩu', 'Password'),
            'repassword' => 'Nhập lại Mật khẩu',
            'code' => 'Mã nhân viên',
            'firstname' => 'Tên nhân viên',
            'lastname' => 'Họ nhân viên',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'dob' => 'Ngày sinh',
            'address' => 'Địa chỉ',
            'gender' => 'Giới tính',
            'identity_card' => 'Số CMND',
            'date_range' => 'Ngày cấp',
            'issued_by' => 'Nơi cấp',
            'contract_signing_date' => 'Ngày kí hợp đồng lao động',
            'effective_date' => 'Ngày hiệu lực',
            'expiration_date' => 'Ngày kết thúc',
            'join_company' => 'Ngày vào ngân hàng',
            'status' => 'Trạng thái',
            'title_id' => 'Chức danh',
            'unit_id' => 'Đơn vị',
            'area_id' => 'Khu vực',
            'expbank' => 'Thâm niên trong lĩnh vực ngân hàng',
            'avatar' => 'Ảnh đại diện',
            'certificate_code' => 'Trình độ',
            'id_code' => 'Mã định danh',
            'referer' => 'Mã người giới thiệu'
        ];
    }

    public static function fullname($user_id = null)
    {
        if (!Auth::check()) {
            return '';
        }
        $user_id = empty($user_id) ? Auth::id() : $user_id;
        return ProfileView::where('user_id', $user_id)->value('full_name');
    }

    public static function usercode($user_id = null)
    {
        if (!Auth::check()) {
            return '';
        }

        $user_id = empty($user_id) ? Auth::id() : $user_id;
        $user = self::where('user_id', $user_id)->first();
        return $user ? $user->code : '';
    }

    public static function email($user_id = null)
    {
        if (!Auth::check()) {
            return '';
        }

        $user_id = empty($user_id) ? Auth::id() : $user_id;
        $user = self::where('user_id', $user_id)->first();
        return $user ? $user->email : '';
    }

    public static function avatar($user_id = null)
    {
        $user_id = $user_id ?? Auth::id();
        $user = Profile::find($user_id);
        return $user ? $user->getAvatar() : asset('/images/user_tt.png');
    }

    public static function hasRole($user_id = null)
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->roles()->count();
        //        $user_id = empty($user_id) ? Auth::id() : $user_id;
        //        $user = self::where('user_id', $user_id)->whereNotNull('role')->first();
        //        if ($user){
        //            return true;
        //        }
        //        return false;
    }
    protected $appends = ['full_name'];
    public function getFullNameAttribute($value)
    {
        return  "{$this->lastname} {$this->firstname}";
    }
    //    public function getGenderAttribute($value){
    //        return   ($value==1?'Nam':'Nữ');
    //    }

    //    public function quizpart()
    //    {
    //        return $this->belongsTo(QuizRegister::class,'user_id');
    //    }

    public static function usertype($user_id = null)
    {
        if (!Auth::check()) {
            return '';
        }

        $user_id = empty($user_id) ? Auth::id() : $user_id;
        $user = self::where('user_id', $user_id)->first();
        return $user ? $user->type_user : 1;
    }

    public static function typeView($model)
    {
        $prefix = \DB::getTablePrefix();
        $user_id = Auth::id();
        $result = Permission::join('el_user_permission_type', 'el_user_permission_type.permission_id', '=', 'el_permissions.id')
            ->join('el_permission_type', 'el_permission_type.id', '=', 'el_user_permission_type.permission_type_id')
            ->where(['el_user_permission_type.user_id' => $user_id])
            ->whereRaw('(' . $prefix . "el_permissions.model= '$model' or concat(" . $prefix . "el_permissions.model,'_view')= '$model')")
            ->select('el_permissions.id', 'el_permission_type.name', 'el_permission_type.type', 'el_permission_type.description', 'el_user_permission_type.permission_type_id')
            ->first();
        return $result;
    }

    public static function getLevel()
    {
        $user_id = Auth::id();
        return \DB::table('el_profile')->join('el_unit', 'el_profile.unit_code', '=', 'el_unit.code')->where('user_id', $user_id)->value('el_unit.level');
    }

    public static function getCompany($user_id = null)
    {
        $user_id = empty($user_id) ? Auth::id() : $user_id;
        return \DB::table('el_profile')
            ->join('el_unit_view', 'el_profile.unit_code', '=', 'el_unit_view.unit_code')
            ->where('user_id', $user_id)
            ->value('el_unit_view.unit0_id');
    }

    public static function getUnitCode()
    {
        $user_id = Auth::id();
        return \DB::table('el_profile')
            ->where('user_id', $user_id)
            ->value('unit_code');
    }
    public static function getUnitId()
    {
        $user_id = Auth::id();
        return \DB::table('el_profile')->join('el_unit', 'el_profile.unit_code', 'el_unit.code')
            ->where('el_profile.user_id', $user_id)->value('el_unit.id');
    }
    public static function getTItleId()
    {
        $user_id = Auth::id();
        return \DB::table('el_profile')->where('user_id', $user_id)->value('title_id');
    }

    public static function getUnitManagerByUser()
    {
        $user = \Auth::user();
        $role = $user->roles()->value('id');
        $units = \DB::table('el_role_has_permission_type as a')->join('el_permission_type_unit as b', 'a.permission_type_id', '=', 'b.permission_type_id')
            ->join('el_unit as c', 'c.id', '=', 'b.unit_id')->where('a.role_id', $role)->get(['id', 'code', 'name']);
        $data = [];
        foreach ($units as $index => $unit) {
            $data[] = (int)$unit->id;
        }
        $unitmanagers = UnitManager::getUnitManagedByUser();
        foreach ($unitmanagers as $index => $unitmanager) {
            $data[] = (int)$unitmanager->id;
        }
        return $data;
    }
}
