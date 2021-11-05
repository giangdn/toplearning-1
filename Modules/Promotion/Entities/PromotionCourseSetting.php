<?php

namespace Modules\Promotion\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Promotion\Entities\PromotionCourseSetting
 *
 * @property int $id
 * @property int $course_id
 * @property int $type
 * @property int $method
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $point
 * @property bool $status
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionCourseSetting whereStatus($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Promotion\Entities\PromotionMethodSetting[] $methodSetting
 * @property-read int|null $method_setting_count
 */
class PromotionCourseSetting extends Model
{
    protected $table = 'el_promotion_course_setting';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'course_id',
        'type',
        'method',
        'start_date',
        'end_date',
        'min_score',
        'max_score',
        'min_percent',
        'max_percent',
        'point',
        'status'
    ];
    public static function getAttributeName(){
        return [
            'point' => "Điểm thưởng",
            'score' => "Mốc điểm",
        ];
    }

    public function methodSetting()
    {
        return $this->hasMany('Modules\Promotion\Entities\PromotionMethodSetting','setting_id','id');
    }

    public static function getPromotionCourseSetting($course_id, $course_type, $code){
        $query = self::query()
            ->where('course_id', '=', $course_id)
            ->where('type', '=', $course_type)
            ->where('code', '=', $code);
        if ($query->exists()){
            return $query->first();
        }

        return '';
    }
}
