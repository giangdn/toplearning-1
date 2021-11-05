<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC13 extends Model
{
    public static function sql($year, $unit_id, $area_id)
    {
        $result_area_id = explode(',', $area_id);

        $areas = Area::whereIn('id', $result_area_id)->pluck('code')->toArray();
        foreach ($areas as $area_code){
            $arr_id = Area::getArrayChild($area_code);
            $result_area_id = array_merge($result_area_id, $arr_id);
        }
        $unit_by_area = Unit::whereIn('area_id', $result_area_id)->pluck('id')->toArray();

        ReportNewExportBC13::addGlobalScope(new CompanyScope());
        $query = ReportNewExportBC13::query();
        $query->whereIn('unit_id_1', $unit_by_area);

        if ($year){
            $query->where('year', '<=', $year);
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
