<?php

namespace Modules\DashboardUnit\Entities;

use Illuminate\Database\Eloquent\Model;

class DashboardUnitByQuiz extends Model
{
    protected $table = 'el_dashboard_unit_by_quiz';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'unit_name',
        'area_id',
        'total',
        'quiz_type',
        'quiz_type_name',
        'num_user',
        'num_quiz_part',
        'month',
        'year',
    ];
}
