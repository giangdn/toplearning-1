<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

class BC05 extends Model
{
    public static function sql($course_type, $subject_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id, $area_id)
    {
        ReportNewExportBC05::addGlobalScope(new CompanyScope('unit_id_1'));
        $query = ReportNewExportBC05::query();
        $query->select([
            'el_report_new_export_bc05.*',
            'b.name as tb_unit_name',
            'c.name as unit_type_name',
            'd.name as area_name_unit'
        ]);
        $query->from('el_report_new_export_bc05');
        $query->leftjoin('el_profile_view as pv','pv.user_id','=','el_report_new_export_bc05.user_id');
        $query->leftjoin('el_unit as b','b.code','=','pv.unit_code');
        $query->leftjoin('el_unit_type as c','c.id','=','b.type');
        $query->leftjoin('el_area as d','d.id','=','b.area_id');
        $query->whereIn('el_report_new_export_bc05.course_type',explode(',', $course_type));
        if ($subject_id){
            $query->whereIn('el_report_new_export_bc05.subject_id', explode(',', $subject_id));
        }
        if ($from_date){
            $query->where('el_report_new_export_bc05.start_date', '>=', date_convert($from_date, '00:00:00'));
        }
        if ($to_date){
            $query->where(function ($sub) use ($to_date){
                $sub->orWhereNull('el_report_new_export_bc05.end_date');
                $sub->orWhere('el_report_new_export_bc05.end_date', '<=', date_convert($to_date, '23:59:59'));
            });
        }
        if ($training_type_id){
            $query->whereIn('el_report_new_export_bc05.training_type_id', explode(',', $training_type_id));
        }
        if ($title_id){
            $query->whereIn('el_report_new_export_bc05.title_id', explode(',', $title_id));
        }
        if ($area_id) {
            $area = Area::find($area_id);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->WhereIn('d.id', $area_id);
                $sub_query->orWhere('d.id', '=', $area->id);
            });
        }
        if ($unit_id){
            $unit = Unit::find($unit_id);
            $unit_child = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_child, $unit) {
                $sub_query->orWhereIn('el_report_new_export_bc05.unit_id_1', $unit_child);
                $sub_query->orWhere('el_report_new_export_bc05.unit_id_1', '=', $unit->id);
            });
        }

        return $query;
    }

}
