<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use App\Profile;
use Modules\Report\Entities\BC40;

class BC40Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $level_name_area = function ($level) {
            return Area::getLevelName($level);
        };

        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'level_name' => $level_name,
            'level_name_area' => $level_name_area,
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->from_date || !$request->to_date || !$request->unit_id)
            json_result([]);

        $from_date = date_convert($request->from_date);
        $to_date = date_convert($request->to_date);
        $unit = Unit::find($request->unit_id);
        $area = Area::find($request->area_id);
        $course_type = $request->course_type;

        $sort = $request->input('sort', 'user_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = Area::query();
        $query->select([
            'unit.*',
            'area5.code as area_code',
            'area5.name as area_5',
            'area4.name as area_4',
            'area3.name as area_3',
            'area2.name as area_2',
        ]);
        $query->from('el_area as area5');
        $query->leftJoin('el_area as area4', 'area4.code', '=', 'area5.parent_code');
        $query->leftJoin('el_area as area3', 'area3.code', '=', 'area4.parent_code');
        $query->leftJoin('el_area as area2', 'area2.code', '=', 'area3.parent_code');
        $query->leftJoin('el_unit as unit', 'area2.unit_id', '=', 'unit.id');
        if ($unit->level == 2){
            $query->where('unit.parent_code', '=', $unit->code);
        }else{
            $query->where('unit.id', '=', $unit->id);
        }
        if ($area){
            $query->where('area'.$area->level.'.id', '=', $area->id);
        }

        $count = $query->count();
        $query->orderBy('area5.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            if ($unit->level == 2){
                $row->unit_2 = $unit->name;
                $row->unit_3 = $row->name;
            }else{
                $parent = Unit::where('code', '=', $row->parent_code)->first();
                $row->unit_2 = $parent->name;
                $row->unit_3 = $row->name;
            }

            if ($course_type){
                if ($course_type == 1){
                    $row->onl_regsiter = BC40::countRegister($row->code, $row->area_code, $from_date, $to_date, $course_type);
                    $row->onl_completed = BC40::countCompleted($row->code, $row->area_code, $from_date, $to_date, $course_type);
                }else{
                    $row->off_regsiter = BC40::countRegister($row->code, $row->area_code, $from_date, $to_date, $course_type);
                    $row->off_completed = BC40::countCompleted($row->code, $row->area_code, $from_date, $to_date, $course_type);
                }
            }else{
                $row->onl_regsiter = BC40::countRegister($row->code, $row->area_code, $from_date, $to_date, 1);
                $row->off_regsiter = BC40::countRegister($row->code, $row->area_code, $from_date, $to_date, 2);

                $row->onl_completed = BC40::countCompleted($row->code, $row->area_code, $from_date, $to_date, 1);
                $row->off_completed = BC40::countCompleted($row->code, $row->area_code, $from_date, $to_date, 2);
            }

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function dataChart(Request $request) {
        $from_date = date_convert($request->from_date);
        $to_date = date_convert($request->to_date);
        $unit = Unit::find($request->unit_id);
        $area = Area::find($request->area_id);
        $course_type = $request->course_type;

        $query = Area::query();
        $query->select([
            'unit.*',
            'area.id as area_id',
            'area.level as area_level',
            'area.name as area_name',
        ]);
        $query->from('el_area as area');
        if ($area){
            if ($area->level == 2){
                $query->leftJoin('el_area as parent', 'parent.code', '=', 'area.parent_code');
                $query->leftJoin('el_unit as unit', 'parent.unit_id', '=', 'unit.id');
                $query->where('parent.id', '=', $area->id);
            }
            if ($area->level == 3){
                $query->leftJoin('el_area as parent3', 'parent3.code', '=', 'area.parent_code');
                $query->leftJoin('el_area as parent2', 'parent2.code', '=', 'parent3.parent_code');
                $query->leftJoin('el_unit as unit', 'parent2.unit_id', '=', 'unit.id');
                $query->where('parent3.id', '=', $area->id);
            }
            if ($area->level == 4){
                $query->leftJoin('el_area as parent4', 'parent4.code', '=', 'area.parent_code');
                $query->leftJoin('el_area as parent3', 'parent3.code', '=', 'parent4.parent_code');
                $query->leftJoin('el_area as parent2', 'parent2.code', '=', 'parent3.parent_code');
                $query->leftJoin('el_unit as unit', 'parent2.unit_id', '=', 'unit.id');
                $query->where('parent4.id', '=', $area->id);
            }
            if ($area->level == 5){
                $query->leftJoin('el_area as parent4', 'parent4.code', '=', 'area.parent_code');
                $query->leftJoin('el_area as parent3', 'parent3.code', '=', 'parent4.parent_code');
                $query->leftJoin('el_area as parent2', 'parent2.code', '=', 'parent3.parent_code');
                $query->leftJoin('el_unit as unit', 'parent2.unit_id', '=', 'unit.id');
                $query->where('area.id', '=', $area->id);
            }
        }else{
            $query->leftJoin('el_unit as unit', 'area.unit_id', '=', 'unit.id');
        }

        if ($unit->level == 2){
            $query->where('unit.parent_code', '=', $unit->code);
        }else{
            $query->where('unit.id', '=', $unit->id);
        }
        $rows = $query->get();

        $data = [];
        $data[] = [
            'Khu vực',
            'Tổng NV',
            'HV được ghi danh',
            'HV hoàn thành'
        ];

        if (count($rows) > 0) {
            foreach ($rows as $row){
                $area_query = Area::query();
                $area_query->select([
                    'area5.code as area_code',
                ]);
                $area_query->from('el_area as area5');
                $area_query->leftJoin('el_area as area4', 'area4.code', '=', 'area5.parent_code');
                $area_query->leftJoin('el_area as area3', 'area3.code', '=', 'area4.parent_code');
                $area_query->leftJoin('el_area as area2', 'area2.code', '=', 'area3.parent_code');
                $area_query->where('area'.$row->area_level.'.id', '=', $row->area_id);
                $area_query = $area_query->get();

                $regsiter = 0;
                $completed = 0;
                $total_profile = 0;
                foreach ($area_query as $item){
                    if ($course_type){
                        if ($course_type == 1){
                            $regsiter += BC40::countRegister($row->code, $item->area_code, $from_date, $to_date, $course_type);
                            $completed += BC40::countCompleted($row->code, $item->area_code, $from_date, $to_date, $course_type);
                        }else{
                            $regsiter += BC40::countRegister($row->code, $item->area_code, $from_date, $to_date, $course_type);
                            $completed += BC40::countCompleted($row->code, $item->area_code, $from_date, $to_date, $course_type);
                        }
                    }else{
                        $onl_regsiter = BC40::countRegister($row->code, $item->area_code, $from_date, $to_date, 1);
                        $off_regsiter = BC40::countRegister($row->code, $item->area_code, $from_date, $to_date, 2);

                        $onl_completed = BC40::countCompleted($row->code, $item->area_code, $from_date, $to_date, 1);
                        $off_completed = BC40::countCompleted($row->code, $item->area_code, $from_date, $to_date, 2);

                        $regsiter += ($onl_regsiter + $off_regsiter);
                        $completed += ($onl_completed + $off_completed);
                    }
                    $total_profile += Profile::where('unit_code', '=', $row->code)->where('area_code', '=', $item->area_code)->where('status', '=', 1)->count();
                }

                $data[] = [
                    $row->area_name,
                    $total_profile,
                    $regsiter,
                    $completed
                ];
            }
        }else{
            $data[] = [
                '',
                0,
                0,
                0
            ];
        }


        return $data;
    }
}
