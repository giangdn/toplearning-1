<?php

namespace Modules\DashboardUnit\Entities;

use Illuminate\Database\Eloquent\Model;

class DashboardTrainingFormModel extends Model
{
    protected $table = 'el_dashboard_training_form';
    protected $primaryKey = 'id';
    protected $fillable = [
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
