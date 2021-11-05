<?php

namespace Modules\Offline\Entities;

use App\BaseModel;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\Profile;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineRegister
 *
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property int $status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegister newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegister newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegister query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegister whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegister whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegister whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegister whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegister whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineRegister whereUserId($value)
 * @mixin \Eloquent
 * @property-read Profile $user
 */
class OfflineRegister extends BaseModel
{
    use ChangeLogs;
    protected $table = 'el_offline_register';
    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'cron_complete',
        'approved_step',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'user_id' => 'Nhân viên',
            'course_id' => 'Khóa học',
            'status' => 'Trạng thái',
        ];
    }

    public static function checkExists($user_id, $course_id, $status = null){
        $query = self::query();
        $query->where('user_id', '=', $user_id);
        $query->where('course_id', '=', $course_id);
        if (!is_null($status)) {
            $query->where('status', '=', $status);
        }
        return $query->exists();
    }

    public static function getUserRegister($registerId)
    {
        $query = self::query();
//        $query->where('id'=>)?
    }

    public static function countRegisters($course_id)
    {
        $managers =  UnitManager::getIdUnitManagedByUser();

        $query = OfflineRegister::query()
            ->from('el_offline_register AS register')
            ->join('el_profile AS profile', 'profile.user_id', '=', 'register.user_id')
            ->leftJoin('el_unit AS unit', 'unit.code', '=', 'profile.unit_code')
            ->where('register.course_id','=',$course_id);
            /*if (!Permission::isAdmin()){
                $query->whereIn('unit.id', $managers);
            }*/
        return $query->count();
    }

    public function user()
    {
        return $this->belongsTo(Profile::class,'user_id','user_id');
    }
}
