<?php

namespace App;

use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\UnitManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Permission
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string|null $parent_code
 * @property string|null $extend
 * @property int $unit_permission
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereExtend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUnitPermission($value)
 * @property string $guard_name
 * @property int|null $model đối tượng
 * @property string|null $parent mã parent code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 */
class Permission extends Model
{
    protected $table = 'el_permissions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'guard_name',
        'model',
        'parent',
        'extend',
    ];
    public static function getAttributeName() {
        return [
            'name' => 'Mã',
            'description' => 'Tên',
        ];
    }
    public static function hasPermission($code = null) {

        if (empty($code)) {
            if (self::isAdmin()) {
                return true;
            }

            if (self::isTeacher()) {
                return true;
            }

            if (self::isUnitManager()) {
                return true;
            }
        }

        return (self::hasPermissionUser($code) || self::hasPermissionGroup($code));
    }

    public static function hasPermissionUser($code = null) {
        if (self::isAdmin()) {
            return true;
        }

        if (empty($code)) {
            $query = self::query();
            $query->from('el_permission_user');
            $query->where('user_id', '=', Auth::id());
            return $query->exists();
        }

        if (!self::checkPermissionCode($code)) {
            return true;
        }

        $childs = self::getArrayCodeChild($code);
        $query = self::query();
        $query->select(['id'])
            ->from('el_permission_user AS a')
            ->where('a.user_id', '=', Auth::id())
            ->where(function ($subquery) use ($code, $childs) {
                $subquery->where('a.permission_code', '=', $code)
                    ->orWhereIn('a.permission_code', $childs);
            });

        return $query->exists();
    }

    public static function hasPermissionGroup($code = null) {
        if (self::isAdmin()) {
            return true;
        }

        if (empty($code)) {
            $query = self::query();
            $query->from('el_permission_group_user');
            $query->where('user_id', '=', Auth::id());
            return $query->exists();
        }

        if (!self::checkPermissionCode($code)) {
            return true;
        }

        $query = self::query();
        $query->from('el_permission_group AS a');
        $query->join('el_permission_group_detail AS b', 'b.permission_group_id', '=', 'a.id');
        $query->join('el_permission AS c', 'c.id', '=', 'b.permission_id');
        $query->join('el_permission_group_user AS d', 'd.permission_group_id', '=', 'a.id');
        $query->where('d.user_id', '=', Auth::id());
        $query->where('c.code', '=', $code);
        return $query->exists();
    }

    public static function checkPermissionCode($code) {
        $query = self::query();
        $query->from('el_permission');
        $query->where('code', '=', $code);
        return $query->exists();
    }

    public static function isAdmin() {
        if (in_array(@Auth::user()->username,['admin','superadmin']))
            return true;
        return Auth::user() ? Auth::user()->roles()->where('name', 'Admin')->count() : false;
    }
    public static function isSuperAdmin() {
        if (Auth::user()->username == 'superadmin')
            return true;
        return false;
    }
    public static function hasPermissionUnit($code = null) {
        if (!Auth::check()) {
            return false;
        }

        $user = Profile::find(Auth::id());
        $exists1 = UnitManager::where('user_code', $user->code)->exists();
        if (empty($code)) {
            return $exists1;
        }

        return ($exists1 || self::hasPermissionGroup($code) || self::hasPermissionUser($code));
    }

    public static function showMenuManager() {
        return true;
    }

    public static function showMenuTraining() {
        return true;
    }

    public static function showMenuSetting() {
        return true;
    }

    public static function showMenuNews() {
        return true;
    }

    public static function showMenuLibraries() {
        return true;
    }

    public static function showMenuQuiz() {
        return true;
    }

    public static function getChildPermission($code, $prefix='', &$result = []) {
        $rows = Permission::where('parent_code', $code)->get();
        $parent_id = Permission::where('code', $code)->first(['id'])->id;
        foreach ($rows as $row) {
            $result[] = (object) [
                'id' => $row->id,
                'code' => $row->code,
                'name' => $prefix .' '. $row->name,
                'parent_id' => $parent_id
            ];

            self::getChildPermission($row->code, $prefix . ' --', $result);
        }

        return $result;
    }

    public static function getArrayCodeChild($code, &$result = []) {
        $rows = Permission::where('parent_code', $code)->get();
        foreach ($rows as $row) {
            $result[] = $row->code;
            self::getArrayCodeChild($row->code, $result);
        }
        return $result;
    }

    public static function getIdUnitManagerByUser($parent_code, $user_id = null) {
        $user_id = empty($user_id) ? Auth::id() : $user_id;
        $ids1 = UnitManager::getIdUnitManagedByUser($user_id);
        $ids2 = UnitManager::getIdUnitPermissionByUser($parent_code, $user_id);
        return array_merge($ids1, $ids2);
    }

    public static function isTeacher($user_id = null) {
        $user_id = empty($user_id) ? Auth::id() : $user_id;
        if (TrainingTeacher::where('user_id', '=', $user_id)
                ->where('status', '=', 1)->exists()) {
            return true;
        }

        return false;
    }

    public static function isUnitManager($user_id = null, $manager_type = null) {
        $user_id = empty($user_id) ? Auth::id() : $user_id;
        $profile = \DB::table('el_profile')->where('user_id', '=', $user_id)->first();
        if (empty($profile)) {
            return false;
        }

        $unit_manger = \DB::table('el_unit_manager')->where('user_code', '=', $profile->code);
        if ($manager_type){
            $unit_manger = $unit_manger->where('manager_type', '=', $manager_type);
        }

        return $unit_manger->exists();
    }

    public static function isUnitPermistion($user_id = null) {
        $user_id = empty($user_id) ? Auth::id() : $user_id;
        return UnitPermission::where('user_id', '=', $user_id)
            ->exists();
    }

    /*
     * Hàm lấy danh sách các user có quyền
     * Tham số: mã quyền, id đơn vị (nếu có)
     * Trả về: mảng user có quyền truyền vào
     * */
    public static function getUserPermission($permission_code, $unit_id = 0) {
        $query = PermissionUser::query();
        $query->where('permission_code', '=', $permission_code)
            ->where('unit_id', '=', $unit_id);
        return $query->pluck('user_id')->toArray();
    }

    public static function permissionExtend($model)
    {
        return Permission::where(['model'=>$model])->whereNotNull('extend')->value('extend');
    }
}
