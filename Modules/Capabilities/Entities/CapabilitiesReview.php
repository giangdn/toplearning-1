<?php

namespace Modules\Capabilities\Entities;

use Illuminate\Database\Eloquent\Model;

class CapabilitiesReview extends Model
{
    protected $table = 'el_capabilities_review';
    protected $fillable = [
        'user_id',
        'category_name',
        'capabilities_code',
        'capabilities_name',
        'standard_weight',
        'standard_critical_level',
        'standard_level',
        'standard_goal',
        'practical_level',
        'practical_goal',
        'sum_goal',
        'sum_practical_goal',
        'convent_id',
    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên đánh giá',
            'category_name' => 'Nhóm năng lực',
            'capabilities_code' => 'Ký hiệu',
            'capabilities_name' => 'Tên năng lực',
            'standard_weight' => 'Trọng số chuẩn',
            'standard_critical_level' => 'Mức độ quan trọng chuẩn',
            'standard_level' => 'Cấp độ chuẩn',
            'standard_goal' => 'Điểm chuẩn',
            'practical_level' => 'Cấp độ thực tế',
            'practical_goal' => 'Điểm thực tế',
        ];
    }
}
