<?php

namespace Modules\TopicSituations\Entities;

use Illuminate\Database\Eloquent\Model;

class LikeCommentSituation extends Model
{
    protected $table = 'el_like_comment_situation';
    protected $fillable = [
        'user_id',
        'comment_id',
        'reply_comment_id',
    ];
    protected $primaryKey = 'id';
}
