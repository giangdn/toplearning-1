<?php

namespace Modules\Promotion\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Promotion\Entities\PromotionOrders
 *
 * @property int $id
 * @property int $user_id
 * @property string $orders_id
 * @property int $point
 * @property int $quantity
 * @property int $promotion_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders whereOrdersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders wherePromotionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionOrders whereUserId($value)
 * @mixin \Eloquent
 */
class PromotionOrders extends BaseModel
{
    protected $table = 'el_promotion_orders';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'orders_id',
        'point',
        'promotion_id',
        'quantity',
        'status',
        'created_at',
        'updated_at'
    ];
}
