<?php

namespace Modules\Role\Entities;

use Illuminate\Database\Eloquent\Model;

class TitleRole extends Model
{
    public $table = 'el_role_title';
    public $incrementing = false;
    protected $fillable = [
        'title_id',
        'role_id',
    ];
}
