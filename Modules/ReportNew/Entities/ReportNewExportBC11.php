<?php

namespace Modules\ReportNew\Entities;

use Illuminate\Database\Eloquent\Model;

class ReportNewExportBC11 extends Model
{
    protected $table = 'el_report_new_export_bc11';
    protected $primaryKey = 'id';
    protected $fillable = [
        'training_teacher_id',
        'schedule_id',
        'user_id',
        'user_code',
        'fullname',
        'account_number',
        'role_lecturer',
        'role_tuteurs',
        'unit_id_1',
        'unit_code_1',
        'unit_name_1',
        'unit_id_2',
        'unit_code_2',
        'unit_name_2',
        'unit_id_3',
        'unit_code_3',
        'unit_name_3',
        'position_name',
        'title_id',
        'title_code',
        'title_name',
        'course_id',
        'course_code',
        'course_name',
        'course_type',
        'subject_id',
        'subject_name',
        'training_form_id',
        'training_form_name',
        'course_time',
        'time_lecturer',
        'time_tuteurs',
        'start_date',
        'end_date',
        'time_schedule',
        'training_location_id',
        'training_location_name',
        'total_register',
        'cost_lecturer',
        'cost_tuteurs',
    ];
}
