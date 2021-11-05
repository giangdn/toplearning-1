<?php

namespace Modules\DailyTraining\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingSettingScoreComment extends BaseModel
{
    protected $table = 'el_daily_training_setting_score_comment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'from',
        'to',
        'score',
    ];

    public static function getAttributeName() {
        return [
            'from' => 'Từ',
            'to' => 'Đến',
            'score' => 'Điểm',
        ];
    }
}
