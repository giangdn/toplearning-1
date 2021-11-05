<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC26 extends Model
{
    public static function sql($from_date, $to_date, $user_id = null)
    {
        $sub = ReportNewExportBC11::query()
            ->select([DB::raw('MAX(id) as id')])
            ->groupBy([
                'user_id',
                'course_id',
                'course_type',
            ])->pluck('id')->toArray();

        ReportNewExportBC11::addGlobalScope(new CompanyScope('unit_id_1'));
        $query = ReportNewExportBC11::query();
        $query->whereIn('id', $sub);
        if ($user_id){
            $query->where('user_id', '=', $user_id);
        }
        if ($from_date){
            $query->where('start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where('end_date', '<=', date_convert($to_date, '23:59:59'));
        }

        return $query;
    }

}
