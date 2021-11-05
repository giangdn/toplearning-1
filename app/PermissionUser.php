<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\PermissionUser
 *
 * @property string $permission_code
 * @property int $user_id
 * @property int $unit_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionUser wherePermissionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionUser whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PermissionUser whereUserId($value)
 * @mixin \Eloquent
 */
class PermissionUser extends Model
{
    protected $table = 'el_permission_user';
    protected $primaryKey = 'id';
    public $timestamps = null;

    public static function checkExists($code, $user_id, $unit_id = 0) {
        $query = self::query();
        $query->where('permission_code', '=', $code);
        $query->where('user_id', '=', $user_id);
        $query->where('unit_id', '=', $unit_id);
        return $query->first();
    }

    public static function getArrayUserIdPermission($code) {
        $query = self::query();
        $query->select(['user_id'])
        ->where('permission_code', '=', $code)
        ->orWhereIn('permission_code', function ($subquery) use ($code) {
            $subquery->select(['code'])
                ->from('el_permission')
                ->where('parent_code', '=', $code);
        });

        return $query->groupBy(['user_id'])
            ->pluck('user_id')
            ->toArray();
    }

    public static function getUserPermission($code) {
        $query = self::query();
        $query->select(['lastname', 'firstname', 'code', 'user_id'])
            ->from('el_profile')
            ->whereIn('user_id', self::getArrayUserIdPermission($code));

        return $query->get();
    }

    public static function getPermissionNameUser($user_id, $unit_id = 0, $include_code = []) {
        $query = self::query();
        $result = $query->select(['b.name'])
            ->from('el_permission_user AS a')
            ->join('el_permission AS b', 'b.code', '=', 'a.permission_code')
            ->join('el_profile AS c', 'c.user_id', '=', 'a.user_id')
            ->where('a.user_id', '=', $user_id)
            ->where('a.unit_id', '=', $unit_id)
            ->whereIn('b.code', $include_code)
            ->pluck('b.name')
            ->toArray();
        return implode(', ', $result);
    }

    public static function removeByUser($code, $user_id) {
        $query = self::query();
        $query->where('user_id', '=', $user_id)
            ->where(function ($subquery) use ($code, $user_id){
                $subquery->where('permission_code', '=', $code)
                        ->orWhereIn('permission_code', function ($subquery2) use ($code) {
                            $subquery2->select(['permission_code'])
                                ->from('el_permission')
                                ->where('parent_code', '=', $code);
                        });
            });
        return $query->delete();
    }

    public static function hasPermission($code, $user_id, $unit_id) {
        return PermissionUser::where('permission_code', '=', $code)
            ->where('user_id', '=', $user_id)
            ->where('unit_id', '=', $unit_id)
            ->exists();
    }
}
