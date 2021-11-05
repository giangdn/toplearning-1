<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class LoginFail extends Model
{
    protected $table = 'el_login_fail';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'username',
        'user_type',
        'num_fail',
    ];
}
