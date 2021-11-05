<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use App\Automail;

class MailRegisterQuizRemind extends Command
{
    protected $signature = 'mail:register_quiz_remind';

    protected $description = 'Thư nhắc tham dự kỳ thi Đối tượng nhận: Học viên, Trưởng đơn vị Thời gian gửi: trước ngày bắt đầu kỳ thi 1 ngày. cron chạy 4h sáng (0 4 * * *)';
    protected $expression ='0 4 * * *';
    protected $num_day = 1;

    public function __construct()
    {
        parent::__construct();
    }

    /*
     * Setup mỗi ngày chạy 1 lần
     * */
    public function handle()
    {
        $dbprefix = \DB::getTablePrefix();
        $today = date('Y-m-d');
        $plus1day = strtotime(date("Y-m-d", strtotime($today)) . " +{$this->num_day} day");
        $plus1day = strftime("%Y-%m-%d 23:59:59", $plus1day);

        $query = \DB::query();
        $query->select([
            'quiz.id',
            'quiz.name',
            'quiz.code'
        ]);
        $query->from('el_quiz AS quiz')
            ->where('quiz.status', '=', 1)
            ->where(\DB::raw('(SELECT MIN(start_date) FROM '. $dbprefix .'el_quiz_part WHERE quiz_id = '. $dbprefix .'quiz.id)'), '>', date('Y-m-d 00:00:00'))
            ->where(\DB::raw('(SELECT MIN(start_date) FROM '. $dbprefix .'el_quiz_part WHERE quiz_id = '. $dbprefix .'quiz.id)'), '<=', $plus1day);

        if (!$query->exists()) {
            echo 'ok';
            exit();
        }

        $rows = $query->get();
        foreach ($rows as $row) {
            $users = QuizRegister::where('quiz_id', '=', $row->id)->get();

            $parts = QuizPart::where('quiz_id', '=', $row->id)->first();
            foreach ($users as $user){
                $signature = getMailSignature($user->user_id, $user->type);

                $automail = new Automail();
                $automail->template_code = 'register_quiz_remind';
                $automail->params = [
                    'signature' => $signature,
                    'code' => $row->code,
                    'name' => $row->name,
                    'start_date' => get_date($parts->start_date, 'H:i d/m/Y'),
                    'end_date' => get_date($parts->end_date, 'H:i d/m/Y'),
                    'url' => route('module.quiz'),
                ];
                $automail->users = [$user->user_id];
                $automail->object_id = $row->id;
                $automail->object_type = 'register_quiz_remind';
                $automail->check_exists = true;

                if ($automail->addToAutomail()) {
                    echo "mail:register_quiz_remind ". $row->name ."\n";
                }
                else {
                    echo "mail:register_quiz_remind not ". $row->name ."\n";
                }
            }
        }
    }
}
