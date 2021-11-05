<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;

class AdvertisingPhoto extends BaseModel
{
    protected $table = 'el_advertising_photo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'status',
        'url',
        'created_by',
        'updated_by',
        'unit_by',
        'type',
    ];
}
