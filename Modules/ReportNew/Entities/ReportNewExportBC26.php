<?php

namespace Modules\ReportNew\Entities;

use Illuminate\Database\Eloquent\Model;

class ReportNewExportBC26 extends Model
{
    protected $table = 'el_report_new_export_bc26';
    protected $primaryKey = 'id';
    protected $fillable = [
        'training_plan_id',
        'subject_id',
        'course_action_1',
        'course_action_2',
        'year',
    ];
}
