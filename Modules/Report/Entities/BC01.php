<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Indemnify\Entities\Indemnify;

class BC01 extends Model
{
    public static function sql($user_id, $from_date, $to_date)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $query = Indemnify::query();
        $query->select([
            \DB::raw("(NULLIF(".\DB::getTablePrefix()."a.commit_amount,0) - NULLIF(". \DB::getTablePrefix() ."a.exemption_amount,0)) as cost_commit"),
            'a.user_id',
            'a.commit_date as month_commit',
            'a.date_diff',
            'a.contract',
            "a.cost_indemnify",
            'b.code',
            'b.lastname',
            'b.firstname',
            'c.code as course_code',
            'c.name as course_name',
            'c.start_date',
            'c.end_date',
            'c.commit_date as day_commit',
            'd.day_off'
        ])
        ->from('el_indemnify as a')
        ->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id')
        ->leftJoin('el_offline_course as c', 'c.id', '=', 'a.course_id')
        ->leftJoin('el_total_indemnify as d', 'd.user_id', '=', 'a.user_id')
        ->whereNotNull('a.commit_date')
        ->where('c.commit_date', '>=', $from_date)
        ->where('c.commit_date', '<=', $to_date);
        if ($user_id){
            $query->where('a.user_id', '=', $user_id);
        }

        return $query;
    }
}
