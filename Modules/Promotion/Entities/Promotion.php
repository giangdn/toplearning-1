<?php

namespace Modules\Promotion\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Promotion\Entities\Promotion
 *
 * @property int $id
 * @property int $point
 * @property string $name
 * @property string|null $images
 * @property string|null $period
 * @property string|null $rules
 * @property int $number
 * @property int|null $promotion_group
 * @property string|null $contact
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion wherePromotionGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $code
 * @property int $amount
 * @property int|null $created_by
 * @property int|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\Promotion whereUpdatedBy($value)
 */
class Promotion extends BaseModel
{
    protected $table = 'el_promotion';
    protected $primaryKey = 'id';
    protected $fillable = [
        'point',
        'code',
        'name',
        'images',
        'period',
        'rules',
        'amount',
        'promotion_group',
        'contact',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public static function getAttributeName(){
        return [
            'point' => "Điểm",
            'code' => "Mã chương trình quà tặng",
            'name' => 'Tên chương trình quà tặng',
            'images' => 'Hình ảnh',
            'period' => 'Thời hạn',
            'rules' => 'Thể lệ',
            'number' => 'Số lượng',
            'promotion_group' => 'Danh mục quà tặng',
            'contact' => 'Liên hệ',
        ];
    }

    public static function getPromotionByGroup($group_id)
    {
        $promotions = Promotion::where('el_promotion.status',1)
            ->select('el_promotion.*','el_promotion_group.name as group_name')
            ->join('el_promotion_group', 'el_promotion_group.id','promotion_group')
            ->where('el_promotion_group.id', '=', $group_id)
            ->orderBy('period');

        return $promotions->paginate(8);
    }
}
