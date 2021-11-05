<?php

namespace Modules\Report\Http\Controllers;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Report\Entities\BC25;
use Modules\Report\Entities\BC26;

class BC26Controller extends ReportController
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
        if (!$request->year)
            json_result([]);

        $year = $request->year;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC26::sql($year);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->total = OfflineCourse::where('unit_id', '=', $row->unit_id)
                ->where('subject_id', '=', $row->subject_id)->count();

            $row->quarter_1 = OfflineCourse::where('unit_id', '=', $row->unit_id)
                ->where('subject_id', '=', $row->subject_id)
                ->whereNotNull('unit_id')
                ->where(\DB::raw('year(start_date)'), '=', $year)
                ->where(function($sub){
                    $sub->orWhere(\DB::raw('month(start_date)'), '=', 10)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 11)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 12);
                })->count();

            $row->quarter_2 = OfflineCourse::where('unit_id', '=', $row->unit_id)
                ->where('subject_id', '=', $row->subject_id)
                ->whereNotNull('unit_id')
                ->where(\DB::raw('year(start_date)'), '=', ($year+1))
                ->where(function($sub){
                    $sub->orWhere(\DB::raw('month(start_date)'), '=', 1)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 2)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 3);
                })->count();

            $row->quarter_3 = OfflineCourse::where('unit_id', '=', $row->unit_id)
                ->where('subject_id', '=', $row->subject_id)
                ->whereNotNull('unit_id')
                ->where(\DB::raw('year(start_date)'), '=', ($year+1))
                ->where(function($sub){
                    $sub->orWhere(\DB::raw('month(start_date)'), '=', 4)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 5)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 6);
                })->count();

            $row->quarter_4 = OfflineCourse::where('unit_id', '=', $row->unit_id)
                ->where('subject_id', '=', $row->subject_id)
                ->whereNotNull('unit_id')
                ->where(\DB::raw('year(start_date)'), '=', ($year+1))
                ->where(function($sub){
                    $sub->orWhere(\DB::raw('month(start_date)'), '=', 7)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 8)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 9);
                })->count();
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
