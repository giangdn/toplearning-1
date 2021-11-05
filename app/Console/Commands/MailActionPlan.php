<?php

namespace App\Console\Commands;

use App\Automail;
use App\PlanApp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;

class MailActionPlan extends Command
{
    protected $signature = 'mail:action_plan_reminder_first';

    protected $description = 'Thư Thực hiện Đánh giá hiệu quả đào tạo. Đối tượng nhận: Học viên Thời gian gửi: sau khi kết thúc khóa học 1 ngày. chạy cron lúc 02h sáng (0 2 * * *)';
    protected $expression ='0 2 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        return true;
        $template_code = 'action_plan_reminder_01';
//        $today = date('Y-m-d 00:00:00');
        $ctypes = ['online', 'offline'];

        foreach ($ctypes as $ctype) {
            $type = $ctype == 'online' ? 1 : 2;
            $object_type = 'action_plan_reminder_'. $ctype.'_01';

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
                ->whereRaw(dateDiffSql('end_date',now()).' =1');

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
                        'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                        'full_name' => $user->lastname.' '.$user->firstname,
                        'course_code' => $row->code,
                        'course_name' => $row->name,
                        'start_date' => get_date($row->start_date),
                        'end_date' => get_date($row->end_date),
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
