<?php

namespace Modules\TopicSituations\Entities;

use Illuminate\Database\Eloquent\Model;

class LikeSituation extends Model
{
    protected $table = 'el_like_situation';
    protected $fillable = [
        'user_id',
        'situation_id',
    ];
    protected $primaryKey = 'id';
}
