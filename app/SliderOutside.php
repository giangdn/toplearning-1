<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SliderOutside extends Model
{
    protected $table = 'el_slider_outside';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'status',
        'url',
        'type',
        'created_by',
        'updated_by',
    ];
}
