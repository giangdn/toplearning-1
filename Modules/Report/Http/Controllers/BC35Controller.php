<?php

namespace Modules\Report\Http\Controllers;

use App\Permission;
use App\Profile;
use App\Models\Categories\Unit;
use Illuminate\Http\Request;
use Modules\Report\Entities\BC35;

class BC35Controller extends ReportController
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
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $unit = explode(';', $request->unit)[count(explode(';', $request->unit))  - 1];
        
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        
        $query = BC35::getQuery();
        
        if ($from_date) {
            $from_date = date_convert($from_date, '00:00:00');
            $query->where('course.start_date', '>=', $from_date);
        }
        
        if ($to_date) {
            $to_date = date_convert($to_date, '23:59:59');
            $query->where('course.start_date', '<=', $to_date);
        }
        
        if ($unit) {
            $query->inUnit($unit);
        }
    
        if (!Permission::isAdmin()) {
            $managers = Permission::getIdUnitManagerByUser('module.training_unit');
            if ($managers) {
                $query->whereIn('unit.id', $managers);
            }
        }
        
        $count = $query->count();
        $query->orderBy(\DB::raw('CONCAT ( lastname, \' \', firstname )'), 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        foreach ($rows as $row){
            $updated = Profile::where('user_id', '=', $row->updated_by)->first();
            if ($updated) {
                $row->updated = $updated->lastname . ' '. $updated->firstname . ' ('. $updated->code .')';
            }
            
            switch ($row->status) {
                case 0: $row->status = 'Nghỉ việc';break;
                case 1: $row->status = 'Đang làm';break;
                case 2: $row->status = 'Thử việc';break;
                case 3: $row->status = 'Tạm hoãn';break;
            }
            
            $row->created = get_date($row->created_at);
            
            if ($row->unit_level == 2) {
                $row->level2 = $row->unit_name;
            }
    
            if ($row->unit_level == 3) {
                $row->level2 = Unit::firstOrNew(['code' => $row->unit_parent])->name;
                $row->level3 = $row->unit_name;
            }
    
            if ($row->unit_level == 4) {
                $level3 = Unit::firstOrNew(['code' => $row->unit_parent]);
                $row->level2 = Unit::firstOrNew(['code' => $level3->parent_code])->name;
                $row->level3 = $level3->name;
                $row->level4 = $row->unit_name;
            }
        }
        
        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }
}
