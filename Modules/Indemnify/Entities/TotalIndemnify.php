<?php

namespace Modules\Indemnify\Entities;

use Illuminate\Database\Eloquent\Model;

class TotalIndemnify extends Model
{
    protected $table = 'el_total_indemnify';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'total_indemnify',
        'percent',
        'exemption_amount',
        'total_cost',
    ];
}
