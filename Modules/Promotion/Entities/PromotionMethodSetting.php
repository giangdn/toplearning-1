<?php

namespace Modules\Promotion\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Promotion\Entities\PromotionMethodSetting
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionMethodSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionMethodSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionMethodSetting query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $setting_id
 * @property int $score
 * @property int $point
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionMethodSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionMethodSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionMethodSetting wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionMethodSetting whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionMethodSetting whereSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Promotion\Entities\PromotionMethodSetting whereUpdatedAt($value)
 */
class PromotionMethodSetting extends Model
{
    protected $table = 'el_promotion_method_setting';
    protected $primaryKey = 'id';
    protected $fillable = [
        'setting_id',
        'score',
        'point',
    ];
}
