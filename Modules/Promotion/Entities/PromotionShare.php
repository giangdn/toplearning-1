<?php

namespace Modules\Promotion\Entities;

use Illuminate\Database\Eloquent\Model;

class PromotionShare extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'el_promotion_share';
    protected $fillable = [
        'course_id',
        'type',
        'user_id',
        'user_type',
        'share_key',
    ];
}
