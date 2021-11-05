<?php

namespace Modules\Notify\Entities;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class NotifyTemplate extends BaseModel
{
    protected $table = 'el_notify_template';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'title',
        'content',
        'note',
        'status'
    ];
}
