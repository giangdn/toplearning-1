<?php

namespace Modules\DailyTraining\Entities;

use Illuminate\Database\Eloquent\Model;

class DailyTrainingUserCommentVideo extends Model
{
    protected $table = 'el_daily_training_user_comment_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'video_id',
        'user_id',
        'content',
        'failed'
    ];

    public static function getAttributeName() {
        return [
            'video_id' => 'Video',
            'user_id' => 'Người bình luận',
            'content' => 'Nội dung',
        ];
    }

    public static function checkLikeComment($video, $comment, $type){
        $query = DailyTrainingUserLikeCommentVideo::query();
        $query->where('video_id', '=', $video);
        $query->where('comment_id', '=', $comment);
        $query->where('user_id', '=', \Auth::id());
        if ($type == 1){
            $query->where('like', '=', 1);
        }else{
            $query->where('dislike', '=', 1);
        }

        return $query->exists();
    }
}
