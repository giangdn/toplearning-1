<?php

namespace Modules\ReportNew\Entities;

use Illuminate\Database\Eloquent\Model;

class ReportNewExportBC05 extends Model
{
    protected $table = 'el_report_new_export_bc05';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'course_code',
        'course_name',
        'course_type',
        'subject_id',
        'subject_name',
        'training_unit',
        'training_type_id',
        'training_type_name',
        'training_form_id',
        'training_form_name',
        'training_area_id',
        'training_area_name',
        'course_time',
        'attendance',
        'start_date',
        'end_date',
        'score',
        'result',
        'user_id',
        'user_code',
        'fullname',
        'email',
        'phone',
        'area_id',
        'area_code',
        'area_name',
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
        'status_user',
        'note',
    ];
}
