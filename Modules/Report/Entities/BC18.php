<?php

namespace Modules\Report\Entities;

use App\ProfileView;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Categories\Area;

class BC18 extends Model
{
    public static function sql($from_date, $to_date, $unit_id, $userCode, $userName, $area_id)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        ProfileView::addGlobalScope(new DraftScope('user_id'));
        $user_id = ProfileView::query()->where('type_user', '=', 1)->pluck('user_id')->toArray();

        $query = DB::query();
        $query->from('el_login_history as lh');
        $query->leftjoin('el_profile as p','lh.user_id','=','p.user_id');
        $query->whereIn('lh.user_id', $user_id);

        if($unit_id) {
            $query->where('p.unit_id','=', $unit_id);
        }

        if($userCode) {
            $query->where('p.code', 'like', '%'. $userCode .'%');
        }

        if ($area_id) {
            $query->leftJoin('el_unit AS c', 'c.code', '=', 'p.unit_code');
            $query->leftJoin('el_area AS area', 'area.id', '=', 'c.area_id');
            $area = Area::find($area_id);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if ($userName) {
            $query->where(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $userName . '%');
        }

        $query->where('lh.created_at','>=', $from_date)
            ->where('lh.created_at','<=', $to_date)
            ->select('lh.id', 'lh.user_id', 'lh.user_code', 'lh.user_name', 'lh.created_at', 'lh.updated_at', 'lh.number_hits')
            ->orderBy('lh.user_id', 'asc')
            ->get();

        return $query;
    }

}
