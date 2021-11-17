<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfileView
 *
 * @property int $id
 * @property string $code
 * @property int $user_id
 * @property string $full_name Họ Tên nhân viên
 * @property string|null $dob ngày sinh
 * @property string|null $address
 * @property string|null $email
 * @property string|null $identity_card Số CMND
 * @property string|null $date_range Ngày cấp
 * @property string|null $issued_by Nơi cấp
 * @property int $gender 1:Nam, 0:Nữ
 * @property string|null $phone
 * @property string|null $contract_signing_date Ngày kí hợp đồng lao động
 * @property string|null $effective_date Ngày hiệu lực
 * @property string|null $expiration_date Ngày kết thúc
 * @property string|null $date_off Ngày nghỉ việc
 * @property string|null $join_company Ngày vào ngân hàng
 * @property string|null $expbank Thâm niên trong lĩnh vực ngân hàng
 * @property int|null $position_id id chức vụ
 * @property string|null $position_code Mã chức vụ
 * @property string|null $position_name tên chức vụ
 * @property int|null $title_id id chức danh
 * @property string|null $title_code mã chức danh
 * @property string|null $title_name chức danh
 * @property int|null $unit_id id đơn vị
 * @property string|null $unit_code mã đơn vị
 * @property string|null $unit_name tên đơn vị
 * @property int|null $parent_unit_id id đơn vị cha
 * @property string|null $parent_unit_code mã đơn vị cha
 * @property string|null $parent_unit_name tên đơn vị cha
 * @property int|null $area_id id khu vực
 * @property string|null $area_code mã khu vực
 * @property string|null $area_name Tên khu vực
 * @property string|null $level
 * @property int|null $certificate_id Mã trình độ
 * @property string|null $certificate_name trình độ
 * @property int|null $status_id 0: Nghỉ việc, 1: Đang làm, 2: Thử việc, 3: Tạm hoãn
 * @property string|null $status_name Tên trạng thái
 * @property string|null $avatar
 * @property string|null $id_code Mã định danh
 * @property string|null $referer Mã người giới thiệu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereAreaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereAreaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereCertificateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereContractSigningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereDateOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereDateRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereEffectiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereExpbank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereIdCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereIdentityCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereIssuedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereJoinCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereParentUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereParentUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereParentUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView wherePositionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView wherePositionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereReferer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereStatusName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereTitleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereTitleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereUnitCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $firstname
 * @property string|null $lastname
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileView whereLastname($value)
 */
class ProfileView extends CacheModel
{
    protected $table = 'el_profile_view';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'code',
        'user_id',
        'firstname',
        'lastname',
        'full_name',
        'dob',
        'address',
        'email',
        'identity_card',
        'date_range',
        'issued_by',
        'gender',
        'phone',
        'contract_signing_date',
        'effective_date',
        'expiration_date',
        'date_off',
        'join_company',
        'expbank',
        'position_id',
        'position_code',
        'position_name',
        'title_id',
        'title_code',
        'title_name',
        'unit_id',
        'unit_code',
        'unit_name',
        'parent_unit_id',
        'parent_unit_code',
        'parent_unit_name',
        'area_id',
        'area_code',
        'area_name',
        'level',
        'certificate_id',
        'certificate_name',
        'status_id',
        'status_name',
        'avatar',
        'id_code',
        'referer',
        'type_user',
        'date_title_appointment',
        'end_date_title_appointment',
        'marriage'
    ];
}
