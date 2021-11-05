<?php

namespace Modules\Online\Entities;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineObject
 *
 * @property int $id
 * @property int $course_id
 * @property int $title_id
 * @property int $type
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property int|null $unit_id
 * @property int|null $unit_level
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereUnitLevel($value)
 * @property int|null $area1
 * @property int|null $area2
 * @property int|null $area3
 * @property string|null $area4
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereArea1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereArea2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereArea3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineObject whereArea4($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Titles[] $titles
 * @property-read int|null $titles_count
 * @property-read Unit|null $unit
 */
class OnlineObject extends Model
{
    use ChangeLogs;
    protected $table = 'el_online_object';
    protected $fillable = [
        'course_id',
        'title_id',
        'type',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'title_id' => 'Chức danh',
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

    public function titles()
    {
        return $this->belongsToMany(Titles::class,'el_online_object','id','title_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
}
