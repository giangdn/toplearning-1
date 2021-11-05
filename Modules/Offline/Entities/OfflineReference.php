<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineReference
 *
 * @property int $id
 * @property int $register_id
 * @property int $schedule_id
 * @property string|null $reference
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineReference query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineReference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineReference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineReference whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineReference whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineReference whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineReference whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineReference extends Model
{
    protected $table = 'el_offline_reference';
    protected $fillable = [
        'register_id',
        'schedule_id',
        'reference',        
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'register_id' => 'Học viên ghi danh',
            'schedule_id' => 'Buổi học',
            'reference' => 'Đơn xin phép',
        ];
    }

    public static function checkExists($register_id, $schedule_id){
        $query = self::query();
        $query->where('schedule_id', '=', $schedule_id);
        $query->where('register_id', '=', $register_id);
        return $query->first();
    }
}
