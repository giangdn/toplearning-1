<?php

namespace Modules\DailyTraining\Entities;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Type;

class DailyTrainingUserLikeCommentVideo extends Model
{
    protected $table = 'el_daily_training_user_like_comment_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'video_id',
        'user_id',
        'comment_id',
        'like',
        'dislike',
    ];

    public static function getAttributeName() {
        return [
            'video_id' => 'Video',
            'user_id' => 'Người like',
            'like' => 'like',
            'dislike' => 'dislike',
            'comment_id' => 'Bình luận',
        ];
    }

    public static function countLikeOrDisLike($video, $comment, $type)
    {
        if ($type == 1){
            $count_like_comment = DailyTrainingUserLikeCommentVideo::query()
                ->where('comment_id', '=', $comment)
                ->where('video_id', '=', $video)
                ->where('like', '=', 1)
                ->count();

            return $count_like_comment;
        }else{
            $count_dislike_comment = DailyTrainingUserLikeCommentVideo::query()
                ->where('comment_id', '=', $comment)
                ->where('video_id', '=', $video)
                ->where('dislike', '=', 1)
                ->count();

            return $count_dislike_comment;
        }
    }
}
