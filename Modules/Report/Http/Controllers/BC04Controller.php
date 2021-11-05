<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Categories\TrainingForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Report\Entities\BC04;

class BC04Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        $training_from = TrainingForm::all();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'training_from'=>$training_from
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->fromDate && !$request->toDate)
            json_result([]);
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $training_from = $request->training_from;
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = BC04::getCourses($fromDate,$toDate,$training_from);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $item) {
            $students = null; $attended=null; $complete=null;
            foreach (array_keys($item->getOriginal()) as $i => $e) {
                if ($e=='id') continue;
                if ($e=='students'){
                    $students=OfflineRegister::countRegisters($item->id);
                    $item->{$e}=$students;
                }
                elseif ($e=='course_time'){
                    $course_time = preg_replace("/[^0-9]./", '', $item->{$e});
                    $course_time_unit = preg_replace("/[^a-z]/", '', $item->{$e});

                    switch ($course_time_unit){
                        case 'day': $time_unit = 'Ngày'; break;
                        case 'session': $time_unit = 'Buổi'; break;
                        default : $time_unit = 'Giờ'; break;
                    }

                    $item->{$e} = $course_time ? $course_time . ' ' . $time_unit : '';
                }
                elseif($e=='start_date' || $e=='end_date')
                    $item->{$e}=get_date($item->{$e});
                elseif ($e=='cost_class')
                    $item->{$e}=number_format($item->{$e},0,',','.');
                elseif ($e=='teacher'){
                    $item->{$e}=OfflineTeacher::getTeachers($item->id);
                }elseif ($e=='attended'){
                    $attended =OfflineAttendance::countAttendance($item->id);
                    $item->{$e}=$attended;
                }elseif ($e=='percent_attended'){
                    $item->{$e}=$students? number_format($attended/$students*100,2):null;
                }elseif ($e=='unattended'){
                    $item->{$e}=$students - $attended;
                }elseif ($e=='percent_unattended'){
                    $item->{$e}=$students? number_format(($students - $attended)/$students*100,2):null;
                }elseif ($e=='complete'){
                    $complete = OfflineCourseComplete::countCourseComplete($item->id);
                    $item->{$e}= $complete;
                }elseif ($e=='percent_complete'){
                    $item->{$e}= $students? number_format($complete/$students*100,2):null;
                }elseif ($e=='uncomplete'){
                    $item->{$e}= $students-$complete;
                }elseif ($e=='percent_uncomplete'){
                    $item->{$e}= $students?number_format(($students-$complete)/$students*100,2):null;
                }elseif ($e=='object'){
                    $item->{$e}= OfflineObject::getObjects($item->id)[0]->object;
                }
                else
                    $item->{$e}=$item->{$e};
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
