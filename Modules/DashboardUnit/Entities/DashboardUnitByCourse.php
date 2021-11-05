<?php

namespace Modules\DashboardUnit\Entities;

use Illuminate\Database\Eloquent\Model;

class DashboardUnitByCourse extends Model
{
    protected $table = 'el_dashboard_unit_by_course';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'unit_name',
        'area_id',
        'total',
        'training_form_id',
        'training_form_name',
        'num_user',
        'num_course',
        'course_employee',
        'month',
        'year',
    ];
}
