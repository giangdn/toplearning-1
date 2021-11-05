<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Modules\ReportNew\Entities\BC25;

class BC25Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $months = $request->input('month',1);
        $report = parent::reportList();

        Subject::addGlobalScope(new DraftScope());
        $subjects = Subject::where('status',1)->get();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'months' =>$months,
            'subjects' => $subjects,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $subject_id = $request->subject_id;
        $month = (int)$request->month;
        $year = (int)$request->year;
        $isSubmit = $request->isSubmit;
        if (!$isSubmit)
            return;
        $query = BC25::sql($month,$year, $subject_id);
        $count = $query->count();
        $query->orderBy($sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $index => $row) {

            $sum_class=0;$sum_attend=0;$sum_completed=0;$sum_uncompleted=0;
            for ($i=1;$i<=$month;$i++){
                $class =  $row->{"class_$i"};
                $attend = (int)$row->{"attend_$i"};
                $completed = (int)$row->{"completed_$i"};
                $row->{"uncompleted_$i"} = $attend? $attend - $completed: null;

                $sum_class+= $class;
                $sum_attend+= $attend;
                $sum_completed+= $completed;
                $sum_uncompleted+= $attend - $completed;
            }
            $row->sum_class = $sum_class;
            $row->sum_attend = $sum_attend;
            $row->sum_completed = $sum_completed;
            $row->sum_uncompleted = $sum_uncompleted;

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

}
