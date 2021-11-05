<?php

namespace Modules\PermissionApproved\Entities;

use Illuminate\Database\Eloquent\Model;

class ApprovedObjectLevel extends Model
{
    protected $table = 'el_approved_object_level';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
    ];
}
