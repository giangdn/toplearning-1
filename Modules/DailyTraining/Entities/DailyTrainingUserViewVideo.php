<?php

namespace Modules\DailyTraining\Entities;

use Illuminate\Database\Eloquent\Model;

class DailyTrainingUserViewVideo extends Model
{
    protected $table = 'el_daily_training_user_view_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'video_id',
        'user_id',
        'like',
        'dislike',
        'device',
        'time_view',
    ];

    public static function getAttributeName() {
        return [
            'video_id' => 'Video',
            'user_id' => 'Người xem',
            'like' => 'like',
            'dislike' => 'dislike',
            'device' => 'Thiết bị xem',
            'time_view' => 'Thời gian bắt đầu xem',
        ];
    }
}
