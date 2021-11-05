<?php

namespace Modules\ReportNew\Console;

use App\Models\Categories\StudentCost;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\ReportNew\Entities\ReportNewExportBC13;

class ReportNewBC13Update extends Command
{
    protected $signature = 'report_new_bc13:update';

    protected $description = 'report new bc13 update';
    protected $expression ="0 1 * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $units = Unit::whereStatus(1)->get();
        foreach ($units as $unit){
            $unit_2 = @$unit->parent;
            $unit_3 = @$unit_2->parent;

            $profiles = Profile::query()
                ->select([
                    DB::raw('MONTH(join_company) as month'),
                    DB::raw('YEAR(join_company) as year'),
                ])
                ->where('status', '=', 1)
                ->where('unit_code', '=', $unit->code)
                ->whereNotNull('join_company')
                ->get();

            $actual_number_participants = OfflineRegisterView::query()
                ->where('unit_code', '=', $unit->code)
                ->groupBy(['user_id'])
                ->count('user_id');

            $hits_actual_participation = OfflineRegister::query()
                ->from('el_offline_register as a')
                ->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id')
                ->where('b.status', '=', 1)
                ->where('b.unit_code', '=', $unit->code)
                ->count();

            $student_cost_arr = [];
            $student_cost = StudentCost::whereStatus(1)->get();
            foreach ($student_cost as $item){
                $offline_student_cost = OfflineStudentCost::query()
                    ->from('el_offline_student_cost as a')
                    ->leftJoin('el_offline_register as b', 'b.id', '=', 'a.register_id')
                    ->leftJoin('el_profile as c', 'c.user_id', '=', 'b.user_id')
                    ->where('c.unit_code', '=', $unit->code)
                    ->where('a.cost_id', '=', $item->id)
                    ->sum('a.cost');

                $student_cost_arr['student_cost'.$item->id] = @$offline_student_cost;
            }

            $traing_cost_arr = [];
            $traing_cost = TrainingCost::query()->get();
            $offline_course = OfflineCourse::whereStatus(1)->get();
            foreach ($traing_cost as $cost){
                $course_cost_by_training_cost = 0;
                foreach ($offline_course as $course){
                    $total_register_unit = OfflineRegisterView::query()
                        ->where('course_id', '=', $course->id)
                        ->where('status', '=', 1)
                        ->where('unit_id', '=', $unit->id)
                        ->count();

                    $register = OfflineRegister::whereCourseId($course->id)->where('status', '=', 1)->count();
                    $total_register = ($register ? $register : 1);

                    $course_cost = OfflineCourseCost::query()
                        ->where('course_id', $course->id)
                        ->where('cost_id', $cost->id)
                        ->sum('actual_amount');

                    $course_cost_by_training_cost += (($course_cost / $total_register) * $total_register_unit);
                }

                $traing_cost_arr['traing_cost'.$cost->id] = $course_cost_by_training_cost;
            }

            $t1 = $t2 = $t3 = $t4 = $t5 = $t6 = $t7 = $t8 = $t9 = $t10 = $t11 = $t12 = 0;
            foreach ($profiles as $profile){
                $check = ReportNewExportBC13::query()->where('unit_id_1', '=', $unit->id)->first();
                if ($check && $check->year != $profile->year){
                    $t1 = $t2 = $t3 = $t4 = $t5 = $t6 = $t7 = $t8 = $t9 = $t10 = $t11 = $t12 = 0;
                }
                switch ($profile->month){
                    case 1: $t1 += 1; break;
                    case 2: $t2 += 1; break;
                    case 3: $t3 += 1; break;
                    case 4: $t4 += 1; break;
                    case 5: $t5 += 1; break;
                    case 6: $t6 += 1; break;
                    case 7: $t7 += 1; break;
                    case 8: $t8 += 1; break;
                    case 9: $t9 += 1; break;
                    case 10: $t10 += 1; break;
                    case 11: $t11 += 1; break;
                    case 12: $t12 += 1; break;
                }

                ReportNewExportBC13::query()->updateOrCreate([
                    'unit_id_1' => $unit->id,
                    'year' => $profile->year,
                ], [
                    'unit_id_1' => $unit->id,
                    'unit_code_1' => $unit->code,
                    'unit_name_1' => $unit->name,
                    'unit_id_2' => @$unit_2->id,
                    'unit_code_2' => @$unit_2->code,
                    'unit_name_2' => @$unit_2->name,
                    'unit_id_3' => @$unit_3->id,
                    'unit_code_3' => @$unit_3->code,
                    'unit_name_3' => @$unit_3->name,
                    't1' => $t1,
                    't2' => $t2,
                    't3' => $t3,
                    't4' => $t4,
                    't5' => $t5,
                    't6' => $t6,
                    't7' => $t7,
                    't8' => $t8,
                    't9' => $t9,
                    't10' => $t10,
                    't11' => $t11,
                    't12' => $t12,
                    'year' => $profile->year,
                    'actual_number_participants' => $actual_number_participants,
                    'hits_actual_participation' => $hits_actual_participation,
                    'total_teacher_cost' => 0,
                    'total_organizational_cost' => json_encode($traing_cost_arr),
                    'total_academy_cost' => json_encode($student_cost_arr),
                    'unit_by' => $unit->id,
                ]);
            }
        }
    }
}
