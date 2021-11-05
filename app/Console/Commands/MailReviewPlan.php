<?php

namespace App\Console\Commands;

use App\Automail;
use Illuminate\Console\Command;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;

class MailReviewPlan extends Command
{
    protected $signature = 'mail:review_plan';

    protected $description = 'Thư nhắc Thực hiện đánh giá Đánh giá hiệu quả đào tạo. Đối tượng nhận: Học viên Thời gian gửi: trước 2 ngày khi tới hạn tự đánh giá Đánh giá hiệu quả đào tạo. cron chạy 3h sáng (0 3 * * *)';
    protected $expression ='0 3 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $template_code = 'review_action_plan';
        $ctypes = ['online', 'offline'];

        $today = date('Y-m-d 00:00:00');
        $today2day = strtotime(date("Y-m-d", strtotime($today)) . " +2 day");
        $today2day = strftime("%Y-%m-%d 23:59:59", $today2day);

        foreach ($ctypes as $ctype) {
            $type = $ctype == 'online' ? 1 : 2;
            $object_type = 'review_action_plan_' . $ctype;

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
                ->where(\DB::raw('DATE_ADD(end_date,INTERVAL plan_app_day DAY)'), '>', $today)
                ->where(\DB::raw('DATE_ADD(end_date,INTERVAL plan_app_day DAY)'), '<', $today2day);

            $rows = $query->get();
            foreach ($rows as $row) {
                $regquery = \DB::query();
                $regquery->from('el_'.$ctype.'_register AS register');
                $regquery->join('el_'.$ctype.'_result AS result', 'result.register_id', '=', 'register.id');
                $regquery->join('el_profile as profile','profile.user_id','=','register.user_id');
                $regquery->where('register.course_id', '=', $row->id);
                $regquery->where('register.status', '=', 1);
                $regquery->where('result.result', '=', 1);
                $regquery->whereNotExists(function ($subquery) use ($row, $type) {
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
                foreach ($users as $user) {
                    $signature = getMailSignature($user->user_id);

                    $automail = new Automail();
                    $automail->template_code = $template_code;
                    $automail->params = [
                        'signature' => $signature,
                        'gender' => $user->gender==1?'Anh':'Chị',
                        'full_name' => $user->lastname.' '.$user->firstname,
                        'course_code' => $row->code,
                        'course_name' => $row->name,
                        'start_date' => get_date($row->start_date),
                        'end_date' => get_date($row->end_date),
                        'action_plan' => $row->action_plan,
                        'url' => route('frontend.plan_app'),
                    ];
                    $automail->users = [$user->user_id];
                    $automail->object_id = $row->id;
                    $automail->object_type = $object_type;
                    $automail->check_exists = true;

                    if ($automail->addToAutomail()) {
                        echo "mail:review_plan $ctype " . $row->name . "\n";
                    } else {
                        echo "mail:review_plan $ctype not " . $row->name . "\n";
                    }
                }

            }
        }
    }
}
