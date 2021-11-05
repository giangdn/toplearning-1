<?php

namespace Modules\DashboardUnit\Console;

use App\CourseView;
use App\Models\Categories\TrainingType;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\Unit;
use Illuminate\Console\Command;
use Modules\DashboardUnit\Entities\DashboardUnitByCourse;
use Modules\DashboardUnit\Entities\DashboardUnitByQuiz;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Online\Entities\OnlineRegisterView;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizType;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DashboardUnitCommand extends Command
{
    protected $signature = 'dashboard_unit:update';
    protected $description = 'Cập nhật dashboard đơn vị. Chạy lúc 23h (0 23 * * *)';
    protected $expression ='0 23 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $get_month = date('m');
        for ($i=1; $i <= (int) $get_month; $i++) { 
            $month = '0'.$i;
            $first_month = date('Y-'.$month.'-01 00:00:00');
            $d = new \DateTime($first_month);
            $last_month = $d->format('Y-m-t 23:59:59');

            $units = Unit::whereStatus(1)->get();
            $training_forms = TrainingForm::get();  
            foreach ($units as $unit){
                //1: NV Tân Tuyển, 2: NV Hiện hữu
                for ($i = 1; $i <= 3; $i++){
                    $offline_courses = CourseView::where('course_type',2)
                        ->where('isopen',1)
                        ->where('status',1)
                        ->where('course_employee', '=', $i)
                        ->where('start_date','<=',$last_month)
                        ->where(function ($sub) use ($first_month,$last_month){
                            $sub->where('end_date','>=',$last_month);
                            $sub->orwhere('end_date', '>=', $first_month);
                        })
                        ->pluck('course_id')
                        ->toArray();

                    $user_register_by_unit = OfflineRegisterView::query()
                        ->where('status', '=', 1)
                        ->where('unit_id', '=', $unit->id)
                        ->whereIn('course_id', $offline_courses)
                        ->count();

                    $course_by_unit = OfflineResult::query()
                        ->from('el_offline_result as a')
                        ->leftjoin('el_profile_view as b','b.user_id','=','a.user_id')
                        ->where('b.unit_id', '=', $unit->id)
                        ->where('a.result', '=', 1)
                        ->whereIn('a.course_id', $offline_courses)
                        ->groupBy('a.course_id')
                        ->pluck('a.course_id')
                        ->toArray();
                    if($i != 3) {
                        $user_register = $user_register_by_unit;
                        $count_course_by_unit = count($course_by_unit);
                    } else {
                        $user_register = 0;
                        $count_course_by_unit = 0;
                    }
                    DashboardUnitByCourse::updateOrCreate([
                        'unit_id' => $unit->id,
                        'course_employee' => $i,
                        'month' => $month,
                        'year' => date('Y'),
                    ], [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'area_id' => $unit->area_id,
                        'course_employee' => $i,
                        'num_user' => $user_register,
                        'num_course' => $count_course_by_unit,
                        'month' => $month,
                        'year' => date('Y'),
                    ]);
                }

                $quiz_types = QuizType::get();
                foreach ($quiz_types as $quiz_type){
                    $user_register_by_unit = QuizRegister::query()
                        ->whereIn('user_id', function ($sub) use ($unit){
                            $sub->select(['user_id'])
                                ->from('el_profile')
                                ->where('unit_id', '=', $unit->id)
                                ->pluck('user_id')->toArray();
                        })
                        ->where('type', '=', 1)
                        ->whereIn('quiz_id', function ($sub2) use ($quiz_type){
                            $sub2->select(['id'])
                                ->from('el_quiz')
                                ->where('status', '=', 1)
                                ->where('type_id', '=', $quiz_type->id)
                                ->pluck('id')->toArray();
                        })
                        ->where('created_at', '>=', $first_month)
                        ->where('created_at', '<=', $last_month)
                        ->count();

                    $quiz_part_by_unit = QuizRegister::query()
                        ->whereIn('user_id', function ($sub) use ($unit){
                            $sub->select(['user_id'])
                                ->from('el_profile')
                                ->where('unit_id', '=', $unit->id)
                                ->pluck('user_id')->toArray();
                        })
                        ->where('type', '=', 1)
                        ->whereIn('quiz_id', function ($sub2) use ($quiz_type){
                            $sub2->select(['id'])
                                ->from('el_quiz')
                                ->where('status', '=', 1)
                                ->where('type_id', '=', $quiz_type->id)
                                ->pluck('id')->toArray();
                        })
                        ->where('created_at', '>=', $first_month)
                        ->where('created_at', '<=', $last_month)
                        ->groupBy('part_id')
                        ->pluck('part_id')
                        ->toArray();

                    DashboardUnitByQuiz::updateOrCreate([
                        'unit_id' => $unit->id,
                        'quiz_type' => $quiz_type->id,
                        'month' => $month,
                        'year' => date('Y'),
                    ],[
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'area_id' => $unit->area_id,
                        'quiz_type' => $quiz_type->id,
                        'quiz_type_name' => $quiz_type->name,
                        'num_user' => $user_register_by_unit,
                        'num_quiz_part' => count($quiz_part_by_unit),
                        'month' => $month,
                        'year' => date('Y'),
                    ]);
                }
                if ($quiz_type->count() > 0) {
                    DashboardUnitByQuiz::updateOrCreate([
                        'total' => 1,
                        'unit_id' => $unit->id,
                        'quiz_type' => 0,
                        'month' => $month,
                        'year' => date('Y'),
                    ],[
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'area_id' => $unit->area_id,
                        'quiz_type' => 0,
                        'quiz_type_name' => 'Tổng',
                        'num_user' => 0,
                        'num_quiz_part' => 0,
                        'month' => $month,
                        'year' => date('Y'),
                    ]);
                }
            }
        }
        
    }

    public function dashboardUnit($unit, $training_form_id, $user_register_by_unit, $course_by_unit, $month) {
        // dd($course_by_unit);
        DashboardUnitByCourse::updateOrCreate([
            'unit_id' => $unit->id,
            'training_form_id' => $training_form_id->id,
            'month' => $month,
            'year' => date('Y'),
        ], [
            'unit_id' => $unit->id,
            'unit_name' => $unit->name,
            'area_id' => $unit->area_id,
            'training_form_id' => $training_form_id->id,
            'training_form_name' => $training_form_id->name,
            'num_user' => $user_register_by_unit,
            'num_course' => !empty($course_by_unit) ? count($course_by_unit) : 0,
            'month' => $month,
            'year' => date('Y'),
        ]);
    }
}
