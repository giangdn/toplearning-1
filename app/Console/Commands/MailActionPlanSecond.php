<?php

namespace App\Console\Commands;

use App\Automail;
use App\PlanApp;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;

class MailActionPlanSecond extends Command
{
    protected $signature = 'mail:action_plan_reminder_second';

    protected $description = 'Thư Nhắc thực hiện Đánh giá hiệu quả đào tạo. Đối tượng nhận: Học viên Thời gian gửi: sau khi kết thúc khóa học hơn 1 ngày nhưng học viên chưa đánh giá. chạy cron lúc 02h sáng (0 2 * * *)';
    protected $expression ='0 2 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        return true;
        $template_code = 'action_plan_reminder_02';
//        $today = date('Y-m-d 00:00:00');
        $ctypes = ['online', 'offline'];

        foreach ($ctypes as $ctype) {
            $type = $ctype == 'online' ? 1 : 2;
            $object_type = 'action_plan_reminder_'. $ctype.'_02';
            $query = \DB::query();
            $query->select([
                'a.id',
                'a.code',
                'a.name',
                'a.start_date',
                'a.end_date',
                'b.name as action_plan'
            ]);

            $query->from('el_'.$ctype.'_course as a')
                ->join('el_plan_app_template as b','b.id','=','a.plan_app_template')
                ->where('a.status', '=', 1)
                ->where('a.action_plan', '=', 1)
                ->whereRaw(dateDiffSql('end_date',now()->toDateString()).'>1 and '.dateDiffSql('end_date',now()->toDateString()).' <plan_app_day');

            $rows = $query->get();
            foreach ($rows as $row) {
                $regquery = \DB::query();
                $regquery->from('el_'.$ctype.'_register AS register')
                    ->join('el_'.$ctype.'_result AS result','result.register_id', '=', 'register.id')
                    ->join('el_profile as profile','profile.user_id','=','register.user_id')
                    ->where('register.course_id', '=', $row->id)
                    ->where('register.status', '=', 1)
                    ->where('result.result', '=', 1)
                    ->whereNotExists(function ($subquery) use ($row, $type) {
                        $subquery->select(['user_id'])
                            ->from('el_plan_app')
                            ->where('course_id', '=', $row->id)
                            ->where('course_type', '=', $type)
                            ->first();
                    });
                $users = $regquery->select('register.id','register.user_id','profile.firstname','profile.lastname','profile.email','profile.gender')
                    ->get();

                if (empty($users)) {
                    continue;
                }
                foreach ($users as $user){
                    $signature = getMailSignature($user->user_id);

                    $automail = new Automail();
                    $automail->template_code = $template_code;
                    $automail->params = [
                        'signature' => $signature,
                        'gender' => $user->gender=='1'?'Anh':'Chị',
                        'full_name' => $user->lastname.' '.$user->firstname,
                        'course_code' => $row->code,
                        'course_name' => $row->name,
                        'start_date' => $row->start_date,
                        'end_date' => $row->end_date,
                        'action_plan' => $row->action_plan,
                        'url' => route('frontend.plan_app'),
                    ];

                    $automail->users = [$user->user_id];
                    $automail->object_id = $user->id;
                    $automail->object_type = $object_type;
                    if ($automail->addToAutomail()) {
                        echo "mail:action_plan $ctype ". $row->name ."\n";
                    }
                    else {
                        echo "mail:action_plan $ctype not ". $row->name ."\n";
                    }
                }

            }
        }
    }
}
