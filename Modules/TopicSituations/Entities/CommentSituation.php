<?php

namespace Modules\TopicSituations\Entities;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class CommentSituation extends Model
{
    protected $table = 'el_comment_situation';
    protected $fillable = [
        'user_id',
        'topic_id',
        'comment',
        'situation_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'comment' => 'Bình luận',
        ];
    }
}
