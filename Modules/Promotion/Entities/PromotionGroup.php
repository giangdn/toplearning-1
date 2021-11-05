<?php

namespace Modules\Promotion\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Promotion\Entities\PromotionGroup
 *
 * @property int $id
 * @property string $name
 * @property bool $enable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $code
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property bool $status
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionGroup whereUpdatedBy($value)
 * @property string|null $icon
 * @method static \Illuminate\Database\Eloquent\Builder|PromotionGroup whereIcon($value)
 */
class PromotionGroup extends BaseModel
{
    protected $table = 'el_promotion_group';
    protected $primaryKey = 'id';
    protected $fillable = [
        'icon',
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public static function getAttributeName(){
        return [
            'icon' => 'Icon',
            'code' => "Mã nhóm danh mục quà tặng",
            'name' => "Tên nhóm danh mục quà tặng"
        ];
    }
}
