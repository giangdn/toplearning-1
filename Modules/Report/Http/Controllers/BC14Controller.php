<?php

namespace Modules\Report\Http\Controllers;

use App\PlanApp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Report\Entities\BC14;
use function PHPSTORM_META\elementType;

class BC14Controller extends ReportController
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
        if (!$request->from_date && !$request->to_date)
            json_result([]);

        $type = $request->type;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'course_id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC14::sql($type, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
           if ($row->course_type == 1){
               $row->quantily_student = OnlineRegister::where('course_id', '=', $row->id)
                   ->where('status', '=', 1)
                   ->count('user_id');

               if ($row->quantily_student == 0){
                   $row->result_achieved = 0;
                   $row->result_not_achieved = 0;
               }else{
                   $onl_result = OnlineResult::where('course_id', '=', $row->id)
                       ->where('result', '=', 1)
                       ->count('user_id');

                   $onl_not_result = $row->quantily_student - $onl_result;

                   $row->result_achieved = number_format(($onl_result / $row->quantily_student) * 100, 0);
                   $row->result_not_achieved = number_format(($onl_not_result / $row->quantily_student) * 100, 0);
               }

               $row->course_cost = number_format(OnlineCourseCost::where('course_id', '=', $row->id)->sum('actual_amount'), 0, ',', '.');

           }else{
               $register_id = OfflineRegister::where('course_id', '=', $row->id)->pluck('id')->toArray();
               $student_cost = OfflineStudentCost::whereIn('register_id', $register_id)->sum('cost');
               $course_cost = OfflineCourseCost::where('course_id', '=', $row->id)->sum('actual_amount');

               $row->course_cost = number_format($course_cost + $student_cost, 0, ',', '.');

               $row->quantily_student = OfflineRegister::where('course_id', '=', $row->id)
                   ->where('status', '=', 1)
                   ->count('user_id');

               if ($row->quantily_student == 0){
                   $row->result_achieved = 0;
                   $row->result_not_achieved = 0;
               }else {
                   $off_result = OfflineResult::where('course_id', '=', $row->id)
                       ->where('result', '=', 1)
                       ->count('user_id');

                   $off_not_result = $row->quantily_student - $off_result;

                   $row->result_achieved = number_format(($off_result / $row->quantily_student) * 100, 0);
                   $row->result_not_achieved = number_format(($off_not_result / $row->quantily_student) * 100, 0);
               }

               $row->teacher = $this->getTeacher($row->id);
           }

            $row->start_date = get_date($row->start_date, 'd/m/Y');
            $row->end_date = get_date($row->end_date, 'd/m/Y');
            $row->course_type = $row->course_type == 1 ? 'Trực truyến' : 'Tập trung';

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getTeacher($course_id){
        $teacher = OfflineSchedule::leftJoin('el_training_teacher AS b', 'b.id', '=', 'teacher_main_id')
            ->where('course_id', '=', $course_id)
            ->where('b.status', '=', 1)
            ->pluck('b.name')->toArray();

        return $teacher;
    }
}
