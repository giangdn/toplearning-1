<?php

namespace Modules\Forum\Entities;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Type;

class ForumUserLikeComment extends Model
{
    protected $table = 'el_forum_user_like_comment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'thread_id',
        'user_id',
        'comment_id',
        'like',
        'dislike',
    ];

    public static function getAttributeName() {
        return [
            'thread_id' => 'Bài viết',
            'user_id' => 'Người like',
            'like' => 'like',
            'dislike' => 'dislike',
            'comment_id' => 'Bình luận',
        ];
    }

    public static function checkLikeComment($thread_id, $comment, $type){
        $query = ForumUserLikeComment::query();
        $query->where('thread_id', '=', $thread_id);
        $query->where('comment_id', '=', $comment);
        $query->where('user_id', '=', \Auth::id());
        if ($type == 1){
            $query->where('like', '=', 1);
        }else{
            $query->where('dislike', '=', 1);
        }

        return $query->exists();
    }

    public static function countLikeOrDisLike($thread_id, $comment, $type)
    {
        if ($type == 1){
            $count_like_comment = ForumUserLikeComment::query()
                ->where('comment_id', '=', $comment)
                ->where('thread_id', '=', $thread_id)
                ->where('like', '=', 1)
                ->count();

            return $count_like_comment;
        }else{
            $count_dislike_comment = ForumUserLikeComment::query()
                ->where('comment_id', '=', $comment)
                ->where('thread_id', '=', $thread_id)
                ->where('dislike', '=', 1)
                ->count();

            return $count_dislike_comment;
        }
    }
}
