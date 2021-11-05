<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC14 extends Model
{
    public static function sql($type=null, $from_date, $to_date)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');
        $sub = DB::query();
        $sub->from('el_course_view');
            if($type){
                $sub->where('course_type', '=', $type);
            }
        $sub->where('status', '=', 1)
            ->where('start_date','>=', $from_date)
            ->where('start_date','<=', $to_date)
            ->select('id', 'code', 'name', 'course_type', 'training_location_name', 'training_unit', 'start_date', 'end_date')
            ->get();

        return $sub;
    }

}
