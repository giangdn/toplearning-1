<?php

namespace Modules\News\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class NewsLink extends BaseModel
{
    protected $table = 'el_news_link';
    protected $primaryKey = 'id';
    protected $fillable = [
        'news_id',
        'title',
        'link',
        'type',
        'created_by',
        'updated_by',
        'unit_by',
    ];

}
