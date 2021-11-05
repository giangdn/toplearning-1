<?php

namespace Modules\Notify\Entities;

use Illuminate\Database\Eloquent\Model;

class RemoveNotifySend extends Model
{
    protected $table = 'el_remove_notify_send';
    protected $primaryKey = 'id';
    protected $fillable = [];
}
