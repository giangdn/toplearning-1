<?php

namespace Modules\Promotion\Entities;
use Modules\Promotion\Entities\PromotionOrders;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Promotion\Entities\PromotionLevel
 *
 * @property int $id
 * @property int $point
 * @property string $name
 * @property string|null $images
 * @property bool $enable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $code
 * @property int $level
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionLevel whereUpdatedBy($value)
 */
class PromotionLevel extends BaseModel
{
    protected $table = 'el_promotion_level';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'point',
        'level',
        'name',
        'images',
        'status',
        'created_at',
        'updated_at'
    ];

    public static function getAttributeName(){
        return [
            'code' => "Mã cấp bậc",
            'name' => "Tên cấp bậc",
            'level' => "Cấp bậc",
            'point' => "Điểm cần đạt",
            'images' => 'Hình ảnh'
        ];
    }

    public static function levelUp($point,$user_id) {
        $status = ['Từ chối','Hủy'];
        $get_point_order = PromotionOrders::whereNotIn('status',$status)->where('user_id',$user_id)->sum('point');
        if($get_point_order) {
            $calculate_point = $get_point_order + $point;
        } else {
            $calculate_point = $point;
        }
        $level = self::query()->where('point','<=',$calculate_point);
        if($level->exists())
            return $level->max('level');
        else
            return 0;
    }
}
