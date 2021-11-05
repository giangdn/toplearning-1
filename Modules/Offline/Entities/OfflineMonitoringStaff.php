<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineMonitoringStaff
 *
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property string|null $fullname
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineMonitoringStaff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineMonitoringStaff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineMonitoringStaff query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineMonitoringStaff whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineMonitoringStaff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineMonitoringStaff whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineMonitoringStaff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineMonitoringStaff whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineMonitoringStaff whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineMonitoringStaff whereUserId($value)
 * @mixin \Eloquent
 */
class OfflineMonitoringStaff extends Model
{
    protected $table = 'el_offline_course_monitoring_staff';
    protected $fillable = [
        'course_id',
        'user_id',
        'fullname',
        'note',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' => 'Khóa học',
            'user_id' => 'Cán bộ theo dõi',
        ];
    }

    public static function checkExists($course_id, $user_id){
        $query = self::query();
        $query->where('course_id', '=', $course_id);
        $query->where('user_id', '=', $user_id);
        return $query->exists();
    }
}
