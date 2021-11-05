<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class ProfileTakeLeave extends Model
{
    protected $table = 'el_profile_take_leave';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'full_name',
        'absent_code',
        'absent_name',
        'start_date',
        'end_date',
    ];
}
