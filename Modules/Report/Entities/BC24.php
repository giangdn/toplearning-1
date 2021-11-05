<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC24 extends Model
{
    public static function sql($unit_id = null, $from_date, $to_date)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $query = DB::query();
        $query->select(['a.*', 'b.name as unit_name'])
            ->from('el_plan_suggest as a')
            ->leftJoin('el_unit as b','a.unit_code','=','b.code')
            ->where('a.status', '=', 2)
            ->where('a.start_date', '>=', $from_date)
            ->where('a.start_date', '<=', $to_date);
        if ($unit_id){
            $query->where('b.id', '=', $unit_id);
        }

        return $query;
    }

}
