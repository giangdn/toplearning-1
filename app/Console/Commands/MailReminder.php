<?php

namespace App\Console\Commands;

use App\Automail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;

class MailReminder extends Command
{
    protected $signature = 'mail:ReminderCourseOnline';

    protected $description = 'Gửi email nhắc thông báo khóa học online sắp kết thúc nhưng chưa hoàn thành các hoạt động 20 phút/lần (20 * * * *)';
    protected $expression ='20 * * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $data = \DB::query()
            ->select('b.id','b.user_id','a.id as course_id','a.code as course_code','a.name as course_name','a.start_date', 'a.end_date','c.email','c.gender','c.firstname','c.lastname')
            ->from('el_online_course as a')
            ->join('el_online_register as b','a.id','=','b.course_id')
            ->join('el_profile as c','c.user_id','=','b.user_id')
            ->whereNotExists(function (Builder $builder){
                $builder->select('user_id')
                    ->from('el_online_course_complete')
                    ->whereColumn('user_id','=','b.user_id')
                    ->whereColumn('course_id','=','a.id');
            })
            ->whereRaw(dateDiffSql('end_date',now()).' =-2')
            ->get();

        foreach ($data as $item) {
            $total_activity =OnlineCourseActivity::where(['course_id'=>$item->course_id])->count();
            $user_completed_activity =OnlineCourseActivityCompletion::where(['course_id'=>$item->course_id,'user_id'=>$item->user_id,'status'=>1])->count();

            $signature = getMailSignature($item->user_id);
            $params = [
                'signature' => $signature,
                'Duration' => 2,
                'courseType' => 'Online',
                'courseCode' => $item->course_code,
                'courseName' => $item->course_name,
                'startDate' => $item->start_date,
                'endDate' => $item->end_date,
                'Progress' => ($user_completed_activity/$total_activity)*100,
                'Gender' => $item->gender=='1'?'Anh':'Chị',
                'FirstName' => $item->lastname.' '.$item->firstname,
                'url' => route('module.online.detail_online', ['id' => $item->course_id])
            ];
            $automail = new Automail();
            $automail->template_code = 'online_reminder_01';
            $automail->params = $params;
            $automail->users = [$item->user_id];
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $item->id;
            $automail->object_type = 'online_reminder_01';
            $automail->addToAutomail();
        }
        $this->info('Cập nhật thành công');
    }
}
