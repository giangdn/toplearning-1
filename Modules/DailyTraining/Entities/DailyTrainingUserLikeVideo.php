<?php

namespace Modules\DailyTraining\Entities;

use Illuminate\Database\Eloquent\Model;

class DailyTrainingUserLikeVideo extends Model
{
    protected $table = 'el_daily_training_user_like_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'video_id',
        'user_id',
        'like',
        'dislike',
    ];

    public static function getAttributeName() {
        return [
            'video_id' => 'Video',
            'user_id' => 'Người xem',
            'like' => 'like',
            'dislike' => 'dislike',
        ];
    }
}
