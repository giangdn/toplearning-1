<?php

namespace Modules\Online\Entities;

use App\BaseModel;
use App\Models\Categories\UnitManager;
use App\Permission;
use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\Quiz\Entities\Quiz;

/**
 * Modules\Online\Entities\OnlineRegister
 *
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property int $status
 * @property string|null $note
 * @property int|null $unit_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineRegister whereUnitBy($value)
 * @mixin \Eloquent
 * @property-read Profile $user
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $cron_complete
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegister whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegister whereCronComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineRegister whereUpdatedBy($value)
 */
class OnlineRegister extends BaseModel
{
    protected $table = 'el_online_register';
    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'cron_complete',
        'approved_step',
        'user_type',
        'unit_by',
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
        $user_type = getUserType();
        $query = self::query();
        $query->where('user_id', '=', $user_id);
        $query->where('user_type', '=', $user_type);
        $query->where('course_id', '=', $course_id);

        if (!is_null($status)) {
            $query->where('status', '=', $status);
        }

        return $query->exists();
    }

    public static function countRegisters($course_id)
    {
        $managers =  UnitManager::getIdUnitManagedByUser();
        $query = OnlineRegister::query()
            ->from('el_online_register AS register')
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
//    public function courses(){
//        return $this->belongsToMany(OnlineCourse::class,'el_online_course_complete', 'user_id','course_id');
//    }
    public function getUserNotCompleted()
    {
        $query = \DB::query()->select('b.id','b.user_id', 'a.end_date','c.email','c.gender','c.firstname','c.lastname')->from('el_online_course as a')
            ->join('el_online_register as b','a.id','=','b.course_id')
            ->join('el_profile as c','c.user_id','=','b.user_id')
            ->whereNotExists(function (Builder $builder){
                $builder->select('user_id')->from('el_online_course_complete')->whereColumn('user_id','=','b.user_id')->whereColumn('course_id','=','a.id');
            })->whereRaw('DATEDIFF(end_date,'.now().') =-2')->get();
    }
}
