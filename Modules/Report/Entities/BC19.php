<?php

namespace Modules\Report\Entities;

use App\Permission;
use App\Models\Categories\UnitManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC19 extends Model
{
    public static function sql($from_date, $to_date, $user_id, $title_id)
    {
        $units =  UnitManager::getArrayUnitManagedByUser();
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $query = DB::query();
        $query->select([
            'b.id',
            'b.sum_practical_goal',
            'b.sum_goal',
            'c.lastname as lastname',
            'c.firstname as firstname',
            'c.code as user_code',
            'd.name as title_name',
            'e.name as unit_name',
        ]);
        $query->from('el_profile AS c')
            ->leftJoin('el_capabilities_review AS b', 'b.user_id', '=', 'c.user_id')
            ->leftJoin('el_titles AS d', 'd.code', '=', 'c.title_code')
            ->leftJoin('el_unit AS e', 'e.code', '=', 'c.unit_code')
            ->where('b.status', '=', 1)
            ->where('b.created_at','>=', $from_date)
            ->where('b.created_at','<=', $to_date);

        if (Permission::isUnitManager()){
            $query->whereIn('e.id', $units);
        }
        if ($user_id){
            $query->where('c.user_id', '=', $user_id);
        }
        if ($title_id){
            $query->where('d.id', '=', $title_id);
        }

        return $query;
    }
}
