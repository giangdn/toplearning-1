<?php

namespace Modules\Offline\Entities;

use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineAttendance
 *
 * @property int $id
 * @property int $register_id
 * @property int $schedule_id
 * @property int|null $percent
 * @property string|null $note
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineAttendance whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $course_id
 * @property int|null $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineAttendance whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineAttendance whereUserId($value)
 */
class OfflineAttendance extends Model
{
    use ChangeLogs;
    protected $table = 'el_offline_attendance';
    protected $fillable = [
        'register_id',
        'schedule_id',
        'course_id',
        'user_id',
        'absent_reason_id',
        'absent_id',
        'discipline_id',
        'percent',
        'status',
        'note',
        'reference',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'register_id' => 'Học viên ghi danh',
            'schedule_id' => 'Lịch học',
            'percent' => 'Phần trăm tham gia',
            'note' => 'Ghi chú',
            'reference' => 'Đơn xin phép',
        ];
    }

    public static function checkExists($register_id, $schedule_id){
        $query = self::query();
        $query->where('schedule_id', '=', $schedule_id);
        $query->where('register_id', '=', $register_id);
        return $query->first();
    }

    public static function countAttendance($course_id)
    {
        $query = \DB::query()
            ->from('el_offline_register as a')
            ->join('el_offline_attendance as b','a.id','b.register_id')
            ->where('a.course_id','=', $course_id)
            ->count(\DB::raw('DISTINCT '.\DB::getTablePrefix().'a.user_id'));
        return $query;
    }

    public static function updateAttendance($user_id,$course_id,$schedule_id)
    {
        $register_id = OfflineRegister::where('user_id','=',$user_id)->where('course_id','=',$course_id)->where('status','=',1)->value('id');
        if ($register_id)
            return OfflineAttendance::updateOrCreate(
                [
                    'course_id' => $course_id,
                    'register_id' => $register_id,
                    'schedule_id' => $schedule_id,
                ],
                [
                    'user_id' => $user_id,
                    'status' => 1,
                    'percent' => 100
                ]
            );
        else  return false;
    }
}
