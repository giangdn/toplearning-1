<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Profile;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\User\Entities\TrainingProcess;

class BC06 extends Model
{
    public static function sql($joined, $subject_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id, $area_id)
    {
        ReportNewExportBC05::addGlobalScope(new CompanyScope('unit_id_1'));
        $query = ReportNewExportBC05::query();
        $query->select([
            'el_report_new_export_bc05.*',
            'd.name as area_name_unit'
        ]);
        $query->from('el_report_new_export_bc05');
        $query->leftjoin('el_unit as b','b.code','=','el_report_new_export_bc05.unit_code_1');
        $query->leftjoin('el_area as d','d.id','=','b.area_id');

        if ($joined){
            if ($joined == 1){
                $query->whereNotNull('score');
            }
            if ($joined == 2){
                $query->whereNull('score');
            }
        }
        if ($subject_id){
            $query->whereIn('subject_id', explode(',', $subject_id));
        }
        if ($from_date){
            $query->where('start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where('end_date', '<=', date_convert($to_date));
        }
        if ($training_type_id){
            $query->whereIn('training_type_id', explode(',', $training_type_id));
        }
        if ($title_id){
            $query->whereIn('title_id', explode(',', $title_id));
        }
        if ($area_id) {
            $area = Area::find($area_id);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->orWhereIn('d.id', $area_id);
                $sub_query->orWhere('d.id', '=', $area->id);
            });
        }
        if ($unit_id){
            $unit = Unit::find($unit_id);
            $unit_child = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_child, $unit) {
                $sub_query->orWhereIn('unit_id_1', $unit_child);
                $sub_query->orWhere('unit_id_1', '=', $unit->id);
            });
        }

        return $query;
    }
}
