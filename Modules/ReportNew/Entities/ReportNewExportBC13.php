<?php

namespace Modules\ReportNew\Entities;

use Illuminate\Database\Eloquent\Model;

class ReportNewExportBC13 extends Model
{
    protected $table = 'el_report_new_export_bc13';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id_1',
        'unit_code_1',
        'unit_name_1',
        'unit_id_2',
        'unit_code_2',
        'unit_name_2',
        'unit_id_3',
        'unit_code_3',
        'unit_name_3',
        't1',
        't2',
        't3',
        't4',
        't5',
        't6',
        't7',
        't8',
        't9',
        't10',
        't11',
        't12',
        'year',
        'actual_number_participants',
        'hits_actual_participation',
        'total_teacher_cost',
        'total_organizational_cost',
        'total_academy_cost',
        'unit_by',
    ];
}
