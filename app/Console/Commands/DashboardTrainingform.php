<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categories\TrainingForm;
use App\CourseView;
use Modules\Online\Entities\OnlineResult;
use Modules\Offline\Entities\OfflineResult;
use Modules\DashboardUnit\Entities\DashboardTrainingFormModel;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineRegister;

class DashboardTrainingform extends Command
{
    protected $signature = 'dashboard_training_form:update';
    protected $description = 'Cập nhật Thống kê lượt CBNV theo loại hình đào tạo . Chạy lúc 2h (0 2 * * *)';
    protected $expression ='0 2 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $get_month = date('m');
        for ($i=1; $i <= (int) $get_month; $i++) {
            $month = $i < 10 ? '0'.$i : $i;
            $first_month = date('Y-'.$month.'-01 00:00:00');
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $training_forms = TrainingForm::get();
            foreach ($training_forms as $item){
                $user_register_by_unit = 0;
                $course_by_unit = 0 ;

                $online_courses = CourseView::where('course_type',1)
                    ->where('isopen',1)
                    ->where('status',1)
                    ->where('training_form_id',$item->id)
                    ->where('start_date','<=',$last_month)
                    ->where(function ($sub) use ($first_month,$last_month){
                        $sub->where('end_date','>=',$last_month);
                        $sub->orwhere('end_date', '>=', $first_month);
                    })
                    ->pluck('course_id')
                    ->toArray();
                if( !empty($online_courses) ) {
                    $user_register_by_unit = OnlineResult::query()
                        ->from('el_online_result as a')
                        ->join('el_online_register_view as b','b.id','=','a.register_id')
                        ->where('b.status', '=', 1)
                        ->where('a.result', '=', 1)
                        ->whereIn('a.course_id',$online_courses)
                        ->count();
                    $course_by_unit = $online_courses;
                    // $course_by_unit = OnlineResult::query()
                    //     ->from('el_online_result as a')
                    //     ->where('a.result', '=', 1)
                    //     ->whereIn('a.course_id',$online_courses)
                    //     ->groupBy('a.course_id')
                    //     ->pluck('a.course_id')
                    //     ->toArray();
                }

                $offline_courses = CourseView::where('course_type',2)
                    ->where('isopen',1)
                    ->where('status',1)
                    ->where('training_form_id',$item->id)
                    ->where('start_date','<=',$last_month)
                    ->where(function ($sub) use ($first_month,$last_month){
                        $sub->where('end_date','>=',$last_month);
                        $sub->orwhere('end_date', '>=', $first_month);
                    })
                    ->pluck('course_id')
                    ->toArray();
                if(!empty($offline_courses)) {
                    $user_register_by_unit = OfflineResult::query()
                        ->from('el_offline_result as a')
                        ->join('el_offline_register_view as b','b.id','=','a.register_id')
                        ->where('b.status', '=', 1)
                        ->where('a.result', '=', 1)
                        ->whereIn('a.course_id', $offline_courses)
                        ->count();
                    $course_by_unit = $offline_courses;
                    // $course_by_unit = OfflineResult::query()
                    //     ->from('el_offline_result as a')
                    //     ->where('a.result', '=', 1)
                    //     ->whereIn('a.course_id', $offline_courses)
                    //     ->groupBy('a.course_id')
                    //     ->pluck('a.course_id')
                    //     ->toArray();
                }
                $this->dashboardUnit($item, $user_register_by_unit, $course_by_unit, $month);
            }

            DashboardTrainingFormModel::updateOrCreate([
                'total' => 1,
                'training_form_id' => 0,
                'month' => $month,
                'year' => date('Y'),
            ], [
                'total' => 1,
                'training_form_id' => 0,
                'training_form_name' => 'Tổng',
                'num_user' => 0,
                'num_course' => 0,
                'month' => $month,
                'year' => date('Y'),
            ]);
        }

    }

    public function dashboardUnit($training_form_id, $user_register_by_unit, $course_by_unit, $month) {
        DashboardTrainingFormModel::updateOrCreate([
            'training_form_id' => $training_form_id->id,
            'month' => $month,
            'year' => date('Y'),
        ], [
            'training_form_id' => $training_form_id->id,
            'training_form_name' => $training_form_id->name,
            'num_user' => $user_register_by_unit,
            'num_course' => !empty($course_by_unit) ? count($course_by_unit) : 0,
            'month' => $month,
            'year' => date('Y'),
        ]);
    }
}
