<?php

namespace Modules\ReportNew\Http\Controllers;

use App\CourseRegisterView;
use App\CourseView;
use App\Models\Categories\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ReportNew\Entities\BC29;
use Modules\TrainingPlan\Entities\TrainingPlan;
use Modules\TrainingPlan\Entities\TrainingPlanDetail;
use function GuzzleHttp\json_decode;

class BC29Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);
        
        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $year = $request->year;

        if (!$year)
            json_result([]);

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC29::sql($year);
        $count = $query->count();
        $query->orderBy('id', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $subject = Subject::find($row->subject_id);
            $training_plan = TrainingPlan::find($row->training_plan_id);
            $training_plan_detail = TrainingPlanDetail::wherePlanId($row->training_plan_id)->whereSubjectId($row->subject_id)->first();

            $row->subject_code = @$subject->code;
            $row->subject_name = @$subject->name;

            $row->training_plan_code = @$training_plan->code;
            $row->training_plan_name = @$training_plan->name;

            $row->course_action_1 = $row->course_action_1 == 1 ? 'X' : '';
            $row->course_action_2 = $row->course_action_2 == 1 ? 'X' : '';

            $quarter_course_1 = CourseView::query()
                ->where('subject_id', '=', $row->subject_id)
                ->where('in_plan', '=', $row->training_plan_id)
                ->where(\DB::raw('year(start_date)'), '=', $row->year)
                ->where(function($sub){
                    $sub->orWhere(\DB::raw('month(start_date)'), '=', 1)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 2)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 3);
                })
                ->count();

            $quarter_course_2 = CourseView::query()
                ->where('subject_id', '=', $row->subject_id)
                ->where('in_plan', '=', $row->training_plan_id)
                ->where(\DB::raw('year(start_date)'), '=', $row->year)
                ->where(function($sub){
                    $sub->orWhere(\DB::raw('month(start_date)'), '=', 4)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 5)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 6);
                })
                ->count();

            $quarter_course_3 = CourseView::query()
                ->where('subject_id', '=', $row->subject_id)
                ->where('in_plan', '=', $row->training_plan_id)
                ->where(\DB::raw('year(start_date)'), '=', $row->year)
                ->where(function($sub){
                    $sub->orWhere(\DB::raw('month(start_date)'), '=', 7)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 8)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 9);
                })
                ->count();

            $quarter_course_4 = CourseView::query()
                ->where('subject_id', '=', $row->subject_id)
                ->where('in_plan', '=', $row->training_plan_id)
                ->where(\DB::raw('year(start_date)'), '=', $row->year)
                ->where(function($sub){
                    $sub->orWhere(\DB::raw('month(start_date)'), '=', 10)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 11)
                        ->orWhere(\DB::raw('month(start_date)'), '=', 12);
                })
                ->count();

            $quarter_course_arr = [
              '1' => $quarter_course_1,
              '2' => $quarter_course_2,
              '3' => $quarter_course_3,
              '4' => $quarter_course_4,
            ];

            $prefix = DB::getTablePrefix();
            $quarter_user_1 = CourseRegisterView::query()
                ->from('el_course_register_view as a')
                ->leftJoin('el_course_view as b', function ($sub){
                    $sub->on('a.course_id', '=', 'b.course_id');
                    $sub->on('a.course_type', '=', 'b.course_type');
                })
                ->where('b.subject_id', '=', $row->subject_id)
                ->where('b.in_plan', '=', $row->training_plan_id)
                ->where(\DB::raw('year('.$prefix.'a.created_at)'), '=', $row->year)
                ->where(function($sub) use ($prefix){
                    $sub->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 1)
                        ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 2)
                        ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 3);
                })
                ->count();

            $quarter_user_2 = CourseRegisterView::query()
                ->from('el_course_register_view as a')
                ->leftJoin('el_course_view as b', function ($sub){
                    $sub->on('a.course_id', '=', 'b.course_id');
                    $sub->on('a.course_type', '=', 'b.course_type');
                })
                ->where('b.subject_id', '=', $row->subject_id)
                ->where('b.in_plan', '=', $row->training_plan_id)
                ->where(\DB::raw('year('.$prefix.'a.created_at)'), '=', $row->year)
                ->where(function($sub) use ($prefix){
                    $sub->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 4)
                        ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 5)
                        ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 6);
                })
                ->count();

            $quarter_user_3 = CourseRegisterView::query()
                ->from('el_course_register_view as a')
                ->leftJoin('el_course_view as b', function ($sub){
                    $sub->on('a.course_id', '=', 'b.course_id');
                    $sub->on('a.course_type', '=', 'b.course_type');
                })
                ->where('b.subject_id', '=', $row->subject_id)
                ->where('b.in_plan', '=', $row->training_plan_id)
                ->where(\DB::raw('year('.$prefix.'a.created_at)'), '=', $row->year)
                ->where(function($sub) use ($prefix){
                    $sub->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 7)
                        ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 8)
                        ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 9);
                })
                ->count();

            $quarter_user_4 = CourseRegisterView::query()
                ->from('el_course_register_view as a')
                ->leftJoin('el_course_view as b', function ($sub){
                    $sub->on('a.course_id', '=', 'b.course_id');
                    $sub->on('a.course_type', '=', 'b.course_type');
                })
                ->where('b.subject_id', '=', $row->subject_id)
                ->where('b.in_plan', '=', $row->training_plan_id)
                ->where(\DB::raw('year('.$prefix.'a.created_at)'), '=', $row->year)
                ->where(function($sub) use ($prefix){
                    $sub->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 10)
                        ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 11)
                        ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 12);
                })
                ->count();

            $quarter_user_arr = [
                '1' => $quarter_user_1,
                '2' => $quarter_user_2,
                '3' => $quarter_user_3,
                '4' => $quarter_user_4,
            ];

            $plan_year = 0;
            $perform_year = 0;
            for ($i = 1; $i <= 4; $i++){
                $row->{'plan_precious_'.$i} = @$training_plan_detail->{'quarter'.$i};
                $row->{'perform_precious_'.$i} = $quarter_course_arr[$i];
                $row->{'percent_precious_'.$i} = number_format($row->{'perform_precious_'.$i}/($row->{'plan_precious_'.$i} > 0 ? $row->{'plan_precious_'.$i} : 1) * 100, 2);
                $row->{'student_precious_'.$i} = $quarter_user_arr[$i];

                if ($i > 1){
                    $row->{'plan_accumulated_precious_'.$i} = $row->{'plan_accumulated_precious_'.($i-1)} + @$training_plan_detail->{'quarter'.$i};
                    $row->{'perform_accumulated_precious_'.$i} = $row->{'perform_accumulated_precious_'.($i-1)} + $quarter_course_arr[$i];
                    $row->{'percent_accumulated_precious_'.$i} = number_format($row->{'perform_accumulated_precious_'.$i}/($row->{'plan_accumulated_precious_'.$i} > 0 ? $row->{'plan_accumulated_precious_'.$i} : 1) * 100, 2);
                    $row->{'student_accumulated_precious_'.$i} = $row->{'student_accumulated_precious_'.($i-1)} + $quarter_user_arr[$i];
                }else{
                    $row->{'plan_accumulated_precious_'.$i} = @$training_plan_detail->{'quarter'.$i};
                    $row->{'perform_accumulated_precious_'.$i} = $quarter_course_arr[$i];
                    $row->{'student_accumulated_precious_'.$i} = $quarter_user_arr[$i];
                }

                $plan_year += @$training_plan_detail->{'quarter'.$i};
                $perform_year += $quarter_course_arr[$i];
            }

            $row->plan_year = $plan_year;
            $row->perform_year = $perform_year;
            $row->percent_year = number_format($perform_year/($plan_year > 0 ? $plan_year : 1) * 100, 2);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}
