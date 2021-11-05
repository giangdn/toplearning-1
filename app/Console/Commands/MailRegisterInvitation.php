<?php

namespace App\Console\Commands;

use App\Automail;
use Illuminate\Console\Command;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;

class MailRegisterInvitation extends Command
{
    protected $signature = 'mail:register_invitation';

    protected $description = 'Thư mời tham gia khóa học Đối tượng nhận: Học viên, Trưởng đơn vị. Thời gian gửi: trước ngày bắt đầu khóa học 2 ngày.  chạy cron lúc 03h sáng (0 3 * * *)';
    protected $expression ='0 3 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * Thư mời tham gia khóa học {name}
     * Đối tượng nhận: Học viên, Trưởng đơn vị.
     * Thời gian gửi: trước ngày bắt đầu khóa học 2 ngày.
     * Setup 1 ngày chạy 1 lần
     * */
    public function handle()
    {
        $today = date('Y-m-d 00:00:00');
        $today2day = strtotime(date("Y-m-d", strtotime($today)) . " +2 day");
        $today2day = strftime("%Y-%m-%d 23:59:59", $today2day);

        $query = OnlineCourse::query();
        $query->where('status', '=', 1)
            ->where('start_date', '>', $today)
            ->where('start_date', '<', $today2day);

        $rows = $query->get(['id', 'code', 'name', 'start_date', 'end_date']);
        foreach ($rows as $row) {
            $regquery = OnlineRegister::query();
            $regquery->where('course_id', '=', $row->id);
            $regquery->where('status', '=', 1);
            $users = $regquery->get();

            foreach ($users as $user){
                $signature = getMailSignature($user->user_id);
                $automail = new Automail();
                $automail->template_code = 'register_invitation';
                $automail->params = [
                    'signature' => $signature,
                    'code' => $row->code,
                    'name' => $row->name,
                    'start_date' => $row->start_date,
                    'end_date' => $row->end_date
                ];
                $automail->users = [$user->user_id];
                $automail->object_id = $row->id;
                $automail->object_type = 'course_online_invitation';
                $automail->check_exists = true;

                if ($automail->addToAutomail()) {
                    echo "mail:register_invitation ". $row->name ."\n";
                }
                else {
                    echo "mail:register_invitation not ". $row->name ."\n";
                }
            }
        }

        $query = OfflineCourse::query();
        $query->where('status', '=', 1)
            ->where('start_date', '>', $today)
            ->where('start_date', '<', $today2day);

        $rows = $query->get(['id', 'code', 'name', 'start_date', 'end_date']);
        foreach ($rows as $row) {
            $regquery = OnlineRegister::query();
            $regquery->where('course_id', '=', $row->id);
            $regquery->where('status', '=', 1);
            $users = $regquery->get();

            foreach ($users as $user){
                $signature = getMailSignature($user->user_id);
                $automail = new Automail();
                $automail->template_code = 'register_invitation';
                $automail->params = [
                    'signature' => $signature,
                    'code' => $row->code,
                    'name' => $row->name,
                    'start_date' => $row->start_date,
                    'end_date' => $row->end_date
                ];
                $automail->users = [$user->user_id];
                $automail->object_id = $row->id;
                $automail->object_type = 'course_offline_invitation';
                $automail->check_exists = true;

                if ($automail->addToAutomail()) {
                    echo "mail:register_invitation offline ". $row->name ."\n";
                }
                else {
                    echo "mail:register_invitation offline not ". $row->name ."\n";
                }
            }

        }
    }
}
