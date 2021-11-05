<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Capabilities\Entities\Capabilities;
use Modules\Capabilities\Entities\CapabilitiesCategory;
use Modules\Capabilities\Entities\CapabilitiesGroupPercent;
use Modules\Capabilities\Entities\CapabilitiesReviewDetail;
use Modules\Report\Entities\BC19;
use function PHPSTORM_META\elementType;

class BC19Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        $capa_cate = CapabilitiesCategory::get();

        $capa = function ($cate_id){
            return Capabilities::where('category_id', '=', $cate_id)->orderBy('id', 'desc')->get();
        };

        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'capa_cate' => $capa_cate,
            'capa' => $capa
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->from_date && !$request->to_date)
            json_result([]);

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $user_id = $request->user_id;
        $title_id = $request->title_id;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC19::sql($from_date, $to_date, $user_id, $title_id);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $row->user_name = $row->lastname . ' ' . $row->firstname;
            $percent = number_format(($row->sum_practical_goal / $row->sum_goal)*100, 0);

            $group_percent = CapabilitiesGroupPercent::where('from_percent', '<=', $percent)
                ->where(function ($subquery) use ($percent) {
                    $subquery->orWhere('to_percent', '>=', $percent);
                    $subquery->orWhereNull('to_percent');
                })->first();

//            if (empty($row->parent_name)){
//                $row->parent = $row->unit_name;
//                $row->unit = '';
//            }else{
//                $row->parent = $row->parent_name;
//                $row->unit = $row->unit_name;
//            }

            $review_detail = CapabilitiesReviewDetail::where('review_id', '=', $row->id)->get();
            foreach ($review_detail as $detail){
                $row->{'capa_'.$detail->capabilities_id} = ($detail->practical_level < $detail->standard_level ? 'x' : '');
            }
            $row->group_percent = $group_percent ? $group_percent->percent_group : '';
            $row->percent = $percent . ' %';
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
