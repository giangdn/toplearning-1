<?php

namespace Modules\Report\Entities;

use App\Permission;
use App\Models\Categories\UnitManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC20 extends Model
{
    public static function sql($from_date, $to_date)
    {
        $units =  UnitManager::getArrayUnitManagedByUser();
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $query = DB::query();
        $query->select([
            'a.*',
            'c.code as user_code',
            'c.lastname as lastname',
            'c.firstname as firstname',
            'd.name as title_name',
            'e.name as unit_name',
        ]);
        $query->from('el_capabilities_result_detail AS a')
            ->leftJoin('el_capabilities_result AS b', 'b.id', '=', 'a.result_id')
            ->leftJoin('el_profile AS c', 'c.user_id', '=', 'a.user_id')
            ->leftJoin('el_titles AS d', 'd.code', '=', 'c.title_code')
            ->leftJoin('el_unit AS e', 'e.code', '=', 'c.unit_code')
            ->where('b.status', '=', 1)
            ->where('b.updated_at','>=', $from_date)
            ->where('b.updated_at','<=', $to_date);
        if (Permission::isUnitManager()){
            $query->whereIn('e.id', $units);
        }
        return $query;
    }

}
