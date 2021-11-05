<?php

namespace Modules\TopicSituations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class ReplyCommentSituation extends Model
{
    protected $table = 'el_reply_comment_situation';
    protected $fillable = [
        'user_id',
        'comment',
        'comment_id',
        'like',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'comment' => 'Bình luận',
        ];
    }
}
