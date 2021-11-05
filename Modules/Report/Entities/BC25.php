<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC25 extends Model
{
    public static function sql($unit_id, $from_date, $to_date)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $query = DB::query();
        $query->select(['a.*', 'c.name as subject_name'])
            ->from('el_offline_course as a')
            ->leftJoin('el_subject as c', 'a.subject_id', '=', 'c.id')
            ->where('a.status', '=', 1)
            ->where('a.start_date', '>=', $from_date)
            ->where('a.start_date', '<=', $to_date)
            ->where('a.unit_id', 'like', '%'.$unit_id.'%');

        return $query;
    }

}
