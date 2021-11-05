<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC26 extends Model
{
    public static function sql($year)
    {
        $query = DB::query();
        $query->select(['a.*', 'b.id as unit_id', 'b.name as unit_name', 'c.id as subject_id'])
            ->from('el_plan_suggest as a')
            ->leftJoin('el_unit as b','a.unit_code','=','b.code')
            ->leftJoin('el_subject as c', 'a.subject_name', '=', 'c.name')
            ->where('a.status', '=', 2)
            ->whereIn(\DB::raw('year(start_date)'), [$year, ($year+1)]);

        return $query;
    }

}
