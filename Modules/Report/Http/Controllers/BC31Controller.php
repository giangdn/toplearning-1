<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BC31Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
        ]);
    }
    
    public function getData(Request $request) {
        $optradio = $request->optradio;
        $age_from = explode(';', $request->age_from[0]);
        $age_to = explode(';', $request->age_to[0]);
        $units = explode(';', $request->units[0]);
        $begin_day = null;
        $end_day = null;
        $prefix = \DB::getTablePrefix();
        $dob = '(DATEDIFF(hour,'.$prefix.'uprofile.dob,GETDATE())/8766)';
    
        if ($optradio == 1) {
            if ($request->month) {
                $begin_day = $request->month . '-01 00:00:00';
                $end_day = date($request->month . '-t 23:59:59');
            }
        }
    
        if ($optradio == 2) {
            if ($request->year) {
                $begin_day = $request->year . '-01-01 00:00:00';
                $end_day = date($request->year . '-12-t 23:59:59');
            }
        }
    
        $data = [];
        $rows = [];
        $count = 0;
    
        foreach ($age_from as $index => $item) {
            if (empty($item) && empty($age_to[$index])) {
                continue;
            }
        
            $from = $item;
            $to = $age_to[$index];
        
            $query = \DB::query();
            $query->select([
                'unit.name AS unit_name',
                \DB::raw("'$from-$to' AS age"),
                \DB::raw("$from AS afrom"),
                \DB::raw("$to AS ato"),
                'unit.id AS unit_id',
                'unit.level AS unit_level',
                'unit.parent_code',
            ]);
            $query->from('el_analytics_month AS history');
            $query->join('el_profile AS uprofile', 'uprofile.user_id', '=', 'history.user_id');
            $query->join('el_unit AS unit', 'unit.code', '=', 'uprofile.unit_code');
            if ($begin_day) {
                $query->where('history.created_at', '>=', $begin_day);
            }
        
            if ($end_day) {
                $query->where('history.created_at', '<=', $end_day);
            }
        
            if ($from) {
                $query->where(\DB::raw($dob), '>=', $from);
            }
        
            if ($to) {
                $query->where(\DB::raw($dob), '<=', $to);
            }
        
            if ($units[0]) {
                $query->whereIn('unit.id', $units);
            }
        
            $query->groupBy([
                'unit.name',
                'uprofile.dob',
                'unit.level',
                'unit.parent_code',
                'unit.id'
            ]);
            //dd($query->toRawSql());
            $count += $query->count();
            $rows[] = $query->get();
        }
        
        foreach ($rows as $row) {
            $rows2 = $row;
            foreach ($rows2 as $row2) {
                $level2 = null;
                $level3 = null;
            
                if ($row2->unit_level == 2) {
                    $level2 = $row2->unit_name;
                }
            
                if ($row2->unit_level == 3) {
                    $level3 = $row2->unit_name;
                    $unit = Unit::where('code', '=', $row2->parent_code)->first(['name']);
                    if ($unit) {
                        $level2 = $unit->name;
                    }
                }
            
                $data[] = (object) [
                    'age' => $row2->age,
                    'level2' => $level2,
                    'level3' => $level3,
                    'total_access' => $this->getTotalAccess($row2->afrom, $row2->ato, $row2->unit_id, $begin_day, $end_day),
                ];
            }
        }
    
        json_result([
            'total' => $count,
            'rows' => $data
        ]);
    }
    
    private function getTotalAccess($age_from, $age_to, $unit_id, $begin_day, $end_day) {
        $prefix = \DB::getTablePrefix();
        $query = \DB::query();
        $query->from('el_analytics_month AS history');
        $query->join('el_profile AS uprofile', 'uprofile.user_id', '=', 'history.user_id');
        $query->join('el_unit AS unit', 'unit.code', '=', 'uprofile.unit_code');
        if ($begin_day) {
            $query->where('history.created_at', '>=', $begin_day);
        }
        
        if ($end_day) {
            $query->where('history.created_at', '<=', $end_day);
        }
        
        if ($age_from) {
            $query->where(\DB::raw('(DATEDIFF(hour,'.$prefix.'uprofile.dob,GETDATE())/8766)'), '>=', $age_from);
        }
        
        if ($age_to) {
            $query->where(\DB::raw('(DATEDIFF(hour,'.$prefix.'uprofile.dob,GETDATE())/8766)'), '<=', $age_to);
        }
        
        $query->where('unit.id', '=', $unit_id);
        
        return round($query->sum('history.minute') / 60, 2);
    }
}
