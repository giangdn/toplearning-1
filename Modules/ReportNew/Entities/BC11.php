<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC11 extends Model
{
    public static function sql($from_date, $to_date)
    {
        $sub = ReportNewExportBC11::query()
            ->select([DB::raw('MAX(id) as id')])
            ->groupBy([
                'training_teacher_id',
                'user_id',
                'course_id',
                'course_type',
            ])->pluck('id')->toArray();

        ReportNewExportBC11::addGlobalScope(new CompanyScope('unit_id_1'));
        $query = ReportNewExportBC11::query();
        $query->select([
            'el_report_new_export_bc11.training_teacher_id',
            'el_report_new_export_bc11.user_id',
            'el_report_new_export_bc11.user_code',
            'el_report_new_export_bc11.fullname',
            'el_report_new_export_bc11.account_number',
            'el_report_new_export_bc11.role_lecturer',
            'el_report_new_export_bc11.role_tuteurs',
            'el_report_new_export_bc11.unit_id_1',
            'el_report_new_export_bc11.unit_code_1',
            'el_report_new_export_bc11.unit_name_1',
            'el_report_new_export_bc11.unit_id_2',
            'el_report_new_export_bc11.unit_code_2',
            'el_report_new_export_bc11.unit_name_2',
            'el_report_new_export_bc11.unit_id_3',
            'el_report_new_export_bc11.unit_code_3',
            'el_report_new_export_bc11.unit_name_3',
            'el_report_new_export_bc11.title_id',
            'el_report_new_export_bc11.title_code',
            'el_report_new_export_bc11.title_name',
            'el_report_new_export_bc11.course_id',
            'el_report_new_export_bc11.course_code',
            'el_report_new_export_bc11.course_name',
            'el_report_new_export_bc11.course_type',
            'el_report_new_export_bc11.subject_id',
            'el_report_new_export_bc11.subject_name',
            'el_report_new_export_bc11.training_form_id',
            'el_report_new_export_bc11.training_form_name',
            'el_report_new_export_bc11.course_time',
            DB::raw('SUM(time_lecturer) as time_lecturer'),
            DB::raw('SUM(time_tuteurs) as time_tuteurs'),
            'el_report_new_export_bc11.start_date',
            'el_report_new_export_bc11.end_date',
            'el_report_new_export_bc11.training_location_id',
            'el_report_new_export_bc11.training_location_name',
            'el_report_new_export_bc11.total_register',
            'el_report_new_export_bc11.cost_lecturer',
            'el_report_new_export_bc11.cost_tuteurs',
            'b.teacher'
        ]);
        $query->from('el_report_new_export_bc11');
        $query->leftjoin('el_ratting_course as b', function ($join){
            $join->on('el_report_new_export_bc11.course_id','=','b.course_id')
                ->on('el_report_new_export_bc11.course_type','=','b.type');
        });

        $query->whereIn('el_report_new_export_bc11.id', $sub);
        if ($from_date){
            $query->where('el_report_new_export_bc11.start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where('el_report_new_export_bc11.end_date', '<=', date_convert($to_date, '23:59:59'));
        }

        $query->groupBy([
            'el_report_new_export_bc11.training_teacher_id',
            'el_report_new_export_bc11.user_id',
            'el_report_new_export_bc11.user_code',
            'el_report_new_export_bc11.fullname',
            'el_report_new_export_bc11.account_number',
            'el_report_new_export_bc11.role_lecturer',
            'el_report_new_export_bc11.role_tuteurs',
            'el_report_new_export_bc11.unit_id_1',
            'el_report_new_export_bc11.unit_code_1',
            'el_report_new_export_bc11.unit_name_1',
            'el_report_new_export_bc11.unit_id_2',
            'el_report_new_export_bc11.unit_code_2',
            'el_report_new_export_bc11.unit_name_2',
            'el_report_new_export_bc11.unit_id_3',
            'el_report_new_export_bc11.unit_code_3',
            'el_report_new_export_bc11.unit_name_3',
            'el_report_new_export_bc11.title_id',
            'el_report_new_export_bc11.title_code',
            'el_report_new_export_bc11.title_name',
            'el_report_new_export_bc11.course_id',
            'el_report_new_export_bc11.course_code',
            'el_report_new_export_bc11.course_name',
            'el_report_new_export_bc11.course_type',
            'el_report_new_export_bc11.subject_id',
            'el_report_new_export_bc11.subject_name',
            'el_report_new_export_bc11.training_form_id',
            'el_report_new_export_bc11.training_form_name',
            'el_report_new_export_bc11.course_time',
            'el_report_new_export_bc11.time_lecturer',
            'el_report_new_export_bc11.time_tuteurs',
            'el_report_new_export_bc11.start_date',
            'el_report_new_export_bc11.end_date',
            'el_report_new_export_bc11.training_location_id',
            'el_report_new_export_bc11.training_location_name',
            'el_report_new_export_bc11.total_register',
            'el_report_new_export_bc11.cost_lecturer',
            'el_report_new_export_bc11.cost_tuteurs',
            'b.teacher'
        ]);

        //$query->orderBy('a.user_id');
        // dd($query->get()->count());
        return $query;
    }

}
