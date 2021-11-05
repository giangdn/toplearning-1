<?php

namespace Modules\Report\Http\Controllers;

use App\AnalyticsMonth;
use App\Models\Categories\Unit;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Modules\Survey\Entities\Survey;

class BC30Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
        ]);
    }
    
    public function getData(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        
        $optradio = $request->get('optradio');
        $month_from = $request->get('month_from');
        $month_from = date('Y-m', strtotime('01-' . str_replace('/', '-', $month_from)));
        $month_to = $request->get('month_from');
        $month_to = date('Y-m', strtotime('01-' . str_replace('/', '-', $month_to)));
        $year = $request->get('year');
        $status = $request->get('status');
        
        $age_from = explode(';', $request->age_from[0]);
        $age_to = explode(';', $request->age_to[0]);
        $seniority_from = explode(';', $request->seniority_from[0]);
        $seniority_to = explode(';', $request->seniority_to[0]);
        $units = explode(';', $request->unit[0]);
        
        if ($optradio == 1) {
            $users = AnalyticsMonth::where('month', '>=', $month_from)
                ->where('month', '<=', $month_to)
                ->pluck('user_id')
                ->toArray();
        }
        else {
            $users = AnalyticsMonth::where('month', 'like', '%' . $year)
                ->pluck('user_id')
                ->toArray();
        }
        
        $query = \DB::query();
        $query->select([
            'profile.user_id',
            'profile.code',
            \DB::raw('CONCAT(lastname, \' \', firstname) AS name'),
            'title.name AS title_name',
            'unit.id AS unit_id',
            'unit.name AS unit_name',
            'unit.level AS unit_level',
            'unit.parent_code AS parent_code'
        ]);
        
        $query->from('el_profile AS profile')
            ->leftJoin('el_titles AS title', 'title.code', '=', 'profile.title_code')
            ->leftJoin('el_unit AS unit', 'unit.code', '=', 'profile.unit_code');
        
        $query->whereIn('profile.user_id', $users);
        $prefix = \DB::getTablePrefix();
        $dob = '(DATEDIFF(hour,'.$prefix.'profile.dob,GETDATE())/8766)';
        $seniority = '(DATEDIFF(year,'.$prefix . 'profile.join_company,GETDATE()))';
        
        if ($age_from) {
            foreach ($age_from as $index => $item) {
                if (empty($item)) {
                    continue;
                }
                
                $query->where(function (Builder $builder) use ($dob, $index, $item, $age_to) {
                    $builder->orWhere(\DB::raw($dob), '>=', $item);
                    if ($age_to[$index]) {
                        $builder->orWhere(\DB::raw($dob), '<=', $age_to[$index]);
                    }
                });
            }
        }
    
        if ($seniority_from) {
            foreach ($seniority_from as $index => $item) {
                if (empty($item)) {
                    continue;
                }
                
                $query->where(function (Builder $builder) use ($seniority, $index, $item, $seniority_to) {
                    $builder->orWhere(\DB::raw($seniority), '>=', $item);
                    if ($seniority_to[$index]) {
                        $builder->orWhere(\DB::raw($seniority), '<=', $seniority_to[$index]);
                    }
                });
            }
        }
        
        if ($units[0]) {
            $query->whereIn('profile.unit_code', function (Builder $builder) use ($units) {
                $builder->select(['code'])
                    ->from('el_unit')
                    ->whereIn('id', $units);
            });
        }
        
        if (!is_null($status)) {
            $query->where('profile.status', '=', $status);
        }
        
        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        foreach ($rows as $row) {
            if ($optradio == 1) {
                $row->total_access = AnalyticsMonth::where('month', '>=', $month_from)
                    ->where('month', '<=', $month_to)
                    ->where('user_id', '=', $row->user_id)
                    ->sum('access');
            }
            else {
                $row->total_access = AnalyticsMonth::where('month', 'like', '%' . $year)
                    ->where('user_id', '=', $row->user_id)
                    ->sum('access');
            }
    
            if ($optradio == 1) {
                $row->total_hours = round(AnalyticsMonth::where('month', '>=', $month_from)
                        ->where('month', '<=', $month_to)
                        ->where('user_id', '=', $row->user_id)
                        ->sum('minute') / 60, 2);
            }
            else {
                $row->total_hours = round(AnalyticsMonth::where('month', 'like', '%' . $year)
                        ->where('user_id', '=', $row->user_id)
                        ->sum('minute') / 60, 2);
            }
            
            if ($row->unit_level == 2) {
                $row->level2 = $row->unit_name;
            }
            
            if ($row->unit_level == 3) {
                $row->level3 = $row->unit_name;
                $parent = Unit::firstOrNew(['code' => $row->parent_code]);
                $row->level2 = $parent->name;
            }
        }
        
        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
    
    public function dataChart(Request $request) {
    
        $optradio = $request->get('optradio');
        $month_from = $request->get('month_from');
        $month_from = date('Y-m', strtotime('01-' . str_replace('/', '-', $month_from)));
        $month_to = $request->get('month_from');
        $month_to = date('Y-m', strtotime('01-' . str_replace('/', '-', $month_to)));
        $year = $request->get('year');
        $status = $request->get('status');
    
        $age_from = explode(';', $request->age_from[0]);
        $age_to = explode(';', $request->age_to[0]);
        $seniority_from = explode(';', $request->seniority_from[0]);
        $seniority_to = explode(';', $request->seniority_to[0]);
        $units = explode(';', $request->unit[0]);
    
        if ($optradio == 1) {
            $users = AnalyticsMonth::where('month', '>=', $month_from)
                ->where('month', '<=', $month_to)
                ->pluck('user_id')
                ->toArray();
        }
        else {
            $users = AnalyticsMonth::where('month', 'like', '%' . $year)
                ->pluck('user_id')
                ->toArray();
        }
        
        $header = [];
        $header[] = trans('backend.month');
        
        if ($optradio == 1) {
            $form_month = (int) explode('-', $month_from)[0];
            $to_month = (int) explode('-', $month_to)[0];
            $form_year = explode('-', $month_from)[1];
            $to_year = explode('-', $month_to)[1];
    
            if ($form_year == $to_year) {
                for($i=$form_month; $i<=$to_month; $i++) {
                    $ii = $i < 9 ? '0' . $i: $i;
                    $header[] = $form_year . '-' . $ii;
                }
            }
            else {
                for($i = $form_year; $i <= $to_year; $i++) {
                    if ($i == $form_year) {
                        for($j=$form_month; $j<=12; $j++) {
                            $jj = $j < 9 ? '0' . $j: $j;
                            $header[] = $i . '-' . $jj;
                        }
                    }
                    elseif ($i == $to_year) {
                        for($j=1; $j<=$to_month; $j++) {
                            $jj = $j < 9 ? '0' . $j: $j;
                            $header[] = $i . '-' . $jj;
                        }
                    }
                    else {
                        for($j=1; $j<=12; $j++) {
                            $jj = $j < 9 ? '0' . $j: $j;
                            $header[] = $i . '-' . $jj;
                        }
                    }
                }
            }
        }
        
        
        
        $data = [];
        $data[] = $header;
        
        foreach ($forms as $form) {
            $data[] = [
                $form->name,
                
            ];
        }
        
        return \response()->json($data);
    }
}
