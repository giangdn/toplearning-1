<?php

namespace Modules\ReportNew\Console;

use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineCourseView;
use Modules\ReportNew\Entities\BC17;
use Modules\ReportNew\Entities\ReportNewExport;

class ReportBC17 extends Command
{
    protected $signature = 'report:bc17';

    protected $description = 'Báo cáo xác nhận bồi hoàn nhân viên tân tuyển BC17 (1 ngày chạy 1 lần 1h tối)';
    protected $expression ="0 1 * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $courses = OfflineCourseView::where(['course_employee'=>1,'status'=>1])->where('expire_commit','<>',1)->select(
            'id','code','name','training_program_id','training_program_name','subject_id','training_unit','training_type_id','training_type_name','start_date','end_date','schedules','commit_date'
        )->get();
        foreach ($courses as $index => $course) {
            $users = \DB::table('el_offline_register as a')
                ->join('el_profile_view as b','a.user_id','=','b.user_id')
                ->join('el_indemnify as c',function ($query){
                    $query->on('c.user_id','=','a.user_id');
                    $query->on('c.course_id','=','a.course_id');
                })
                ->join('el_unit_view as d','d.unit_code','=','b.unit_code')
                ->where(['a.course_id'=>$course->id,'a.status'=>1])
                ->select(
                    'a.user_id',
                    'b.code','b.full_name','b.email','b.phone','b.area_id','b.area_name','b.position_id','b.position_name','b.title_id','b.title_name','b.unit_id',
                    'c.cost_student','c.commit_date','c.cost_indemnify','c.date_diff','c.compensated', 'd.*'
                    )->get();

            $total_register = ($users->count() ? $users->count() : 1);
            $total_amount = OfflineCourseCost::sumActualAmount($course->id);
            $cost_held = OfflineCourseCost::costHeld($course->id);
            $cost_training = OfflineCourseCost::sumActualAmount($course->id, 2);
            $cost_external = OfflineCourseCost::sumActualAmount($course->id, 3);
            $cost_teacher = OfflineCourseCost::sumActualAmount($course->id, 4);
//            $cost_academy = OfflineCourseCost::sumActualAmount($course->id, 5);
            foreach ($users as $index => $user) {
                $to_time_commit=date('Y-m-d', strtotime($course->commit_date.' + '.$user->commit_date.' days'));
                $endCommit = strtotime($to_time_commit);
                $timeDiff = $endCommit-time();
                if ($user->date_diff){
                    $time_tmp = strtotime($course->commit_date.' + '.$user->date_diff.' days');
                    if ($time_tmp>time()){
                        $d1 = new \DateTime(date('Y-m-d', $time_tmp));
                        $d2 = new \DateTime('now');
                        $d = $d1->diff($d2);
                        $time_rest=$d->days;
                    }else
                        $time_rest=0;
                }
                else{
                    if ($timeDiff>0){
                        $d1 = new \DateTime($to_time_commit);
                        $d2 = new \DateTime('now');
                        $d = $d1->diff($d2);
                        $time_rest = $d->days;
                    }
                    else
                        $time_rest=0;
                }

                $unit = $user->object_id;
                $unit_id = $user->unit_id;
                $unit3_id = $user->{'unit'.$unit.'_id'};
                $unit3_code = $user->{'unit'.$unit.'_code'};
                $unit3_name = $user->{'unit'.$unit};
                $unit2_id = @$user->{'unit'.($unit-1).'_id'};
                $unit2_code = @$user->{'unit'.($unit-1).'_code'};
                $unit2_name = @$user->{'unit'.($unit-1)};
                $unit1_id = @$user->{'unit'.($unit-2).'_id'};
                $unit1_code = @$user->{'unit'.($unit-2).'_code'};
                $unit1_name = @$user->{'unit'.($unit-2)};
                BC17::updateOrCreate(
                  ['user_id'=>$user->user_id,'course_id'=>$course->id],
                  [
                      'user_code'=>$user->code,
                      'full_name'=>$user->full_name,
                      'email'=>$user->email,
                      'phone'=>$user->phone,
                      'area_id'=>$user->area_id,
                      'area'=>$user->area_name,
                      'unit_id'=>$unit_id,
                      'unit1_id'=>$unit1_id,
                      'unit1_code'=>$unit1_code,
                      'unit1_name'=>$unit1_name,
                      'unit2_id'=>$unit2_id,
                      'unit2_code'=>$unit2_code,
                      'unit2_name'=>$unit2_name,
                      'unit3_id'=>$unit3_id,
                      'unit3_code'=>$unit3_code,
                      'unit3_name'=>$unit3_name,
                      'position_id'=>$user->position_id,
                      'position_name'=>$user->position_name,
                      'titles_id'=>$user->title_id,
                      'titles_name'=>$user->title_name,
                      'training_program_id'=>$course->training_program_id,
                      'training_program_name'=>$course->training_program_name,
                      'subject_id'=>$course->subject_id,
                      'course_id'=>$course->id,
                      'course_code'=>$course->code,
                      'course_name'=>$course->name,
                      'training_unit'=>$course->training_unit,
                      'training_type_id'=>$course->training_type_id,
                      'training_type'=>$course->training_type_name,
                      'start_date'=>$course->start_date,
                      'end_date'=>$course->end_date,
                      'time_schedule'=>$course->schedules,
                      'cost_held'=>$cost_held/$total_register, //chi phí tổ chức
                      'cost_training'=>$cost_training/$total_register, // chi phí đào tạo
                      'cost_external'=>$cost_external/$total_register, // chi phí bên ngoài
                      'cost_teacher'=>$cost_teacher/$total_register, // chi phí giảng viên
//                      'cost_academy'=>$cost_academy/$total_register,
                      'cost_student'=>$user->cost_student,
                      'cost_total'=>$total_amount, // tổng chi phí
                      'time_commit'=>$user->commit_date, // thời gian cam kết
                      'from_time_commit'=>$course->commit_date, // cam kết từ ngày
                      'to_time_commit'=> $to_time_commit, // cam kết đến ngày
                      'time_rest'=> $time_rest, // Thời gian cam kết còn lại
                      'cost_refund'=> $user->compensated?0:$user->cost_indemnify, // Chi phí bồi hoàn
                  ]
                );
            }
        }
        $this->updateCourseCommitExpire();
        $this->info('Success');
    }
    private function updateCourseCommitExpire(){ // update cam kết đã hết hạn để ko chạy cron
        $prefix = \DB::getTablePrefix();
        $courses=\DB::table('el_indemnify as a')->join('el_offline_course_view as b','a.course_id','=','b.id')
            ->selectRaw('course_id, max('.$prefix.'a.commit_date) as commit_day, '.$prefix.'b.commit_date')
            ->groupBy(['a.course_id','b.commit_date'])->where(['b.commit'=>1,'b.expire_commit'=>0])->get();
//        $courses = OfflineCourseView::where(['commit'=>1])->select('id','expire_commit')->get();
        foreach ($courses as $index => $course) {
             $maxCommitDate= date('Y-m-d', strtotime($course->commit_date.' +'.$course->commit_day.' days'));
             if (time()>$maxCommitDate){
                 OfflineCourseView::where(['id'=>$course->course_id])->update(['expire_commit'=>1]);
             }

        }
    }

}
