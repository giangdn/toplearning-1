<?php

namespace Modules\ReportNew\Entities;

use Illuminate\Database\Eloquent\Model;

class ReportNewExportBC08 extends Model
{
    protected $table = 'el_report_new_export_bc08';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'course_code',
        'course_name',
        'lecturer',
        'tuteurs',
        'training_form_name',
        'training_type_id',
        'training_type_name',
        'level_subject',
        'training_location',
        'training_unit',
        'title_join',
        'training_object',
        'course_time',
        'start_date',
        'end_date',
        'time_schedule',
        'created_by',
        'registers',
        'join_100',
        'join_75',
        'join_below_75',
        'students_absent',
        'students_pass',
        'students_fail',
        'course_cost',
        'student_cost',
        'total_cost',
        'recruits',
        'exist',
        'plan',
        'incurred',
        'monitoring_staff',
        'monitoring_staff_note',
        'teacher_note',
        'unit_by',
    ];
}
