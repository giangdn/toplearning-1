<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

class BC09 extends Model
{
    public static function sql($training_area_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id)
    {
        ReportNewExportBC05::addGlobalScope(new CompanyScope('unit_id_1'));
        $query = ReportNewExportBC05::query();
        $query->select([
            'el_report_new_export_bc05.*',
            'c.name as unit_type_name',
            'd.name as area_name_unit'
        ]);
        $query->from('el_report_new_export_bc05');
        $query->leftjoin('el_unit as b','b.code','=','el_report_new_export_bc05.unit_code_1');
        $query->leftjoin('el_unit_type as c','c.id','=','b.type');
        $query->leftjoin('el_area as d','d.id','=','b.area_id');
        $query->where('el_report_new_export_bc05.course_type', '=', 2);
        $query->whereIn('el_report_new_export_bc05.course_id', function ($sub){
           $sub->select(['id'])
               ->from('el_offline_course')
               ->where('course_employee', '=', 1)
               ->pluck('id')
               ->toArray();
        });
        if ($training_area_id){
            $area = Area::find($training_area_id);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('d.id', $area_id);
                $sub_query->orWhere('d.id', '=', $area->id);
            });
        }
        if ($from_date){
            $query->where('el_report_new_export_bc05.start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where('el_report_new_export_bc05.end_date', '<=', date_convert($to_date));
        }
        if ($training_type_id){
            $query->whereIn('el_report_new_export_bc05.training_type_id', explode(',', $training_type_id));
        }
        if ($title_id){
            $query->whereIn('el_report_new_export_bc05.title_id', explode(',', $title_id));
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
