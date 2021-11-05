<?php

namespace Modules\Offline\Entities;

use App\Traits\ChangeLogs;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineCondition
 *
 * @property int $id
 * @property int $course_id
 * @property int|null $ratio
 * @property float|null $minscore
 * @property int|null $survey
 * @property int|null $certificate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition whereCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition whereMinscore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition whereRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition whereSurvey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCondition whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineCondition extends Model
{
    use ChangeLogs;
    protected $table = 'el_offline_condition';
    protected $fillable = [
        'course_id',
        'ratio',
        'minscore',
        'survey',
        'certificate',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' => 'Khóa học',
            'ratio' => 'Tỉ lệ %',
            'minscore' => 'Điểm tối thiểu',
            'survey' => 'Thực hiện đánh giá',
            'certificate' => 'Chứng chỉ khóa học',

        ];
    }

    public static function checkExists($course_id)
    {
        $query = self::query();
        $query->where('course_id', '=', $course_id);
        return $query->exists();
    }
}
