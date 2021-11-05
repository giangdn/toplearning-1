<?php

namespace Modules\Offline\Entities;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineObject
 *
 * @property int $id
 * @property int $course_id
 * @property int $title_id
 * @property int $type
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property int|null $unit_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject whereUnitId($value)
 * @property int|null $unit_level
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineObject whereUnitLevel($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Titles[] $titles
 * @property-read int|null $titles_count
 * @property-read Unit|null $unit
 */
class OfflineObject extends Model
{
    use ChangeLogs;
    protected $table = 'el_offline_object';
    protected $fillable = [
        'course_id',
        'title_id',
        'type',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'title' => 'Chức danh',
            'course_id' => 'Khóa học',
            'type' => 'Loại đối tượng',
        ];
    }

    public static function checkObjectExists($title_id, $course_id){
        $query = self::query();
        $query->where('title_id', '=', $title_id);
        $query->where('course_id', '=', $course_id);
        return $query->exists();
    }

    public static function getObjects($course_id)
    {
        $query = \DB::query();
        $obj_name = $query->from('el_offline_object AS a')
            ->join('el_titles AS b', 'b.id', '=', 'a.title_id')
            ->where('a.course_id', '=', $course_id)
            ->pluck('b.name')
            ->toArray();

        return implode(', ', $obj_name);
    }
    public function titles()
    {
        return $this->belongsToMany(Titles::class,'el_offline_object','id','title_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
}
