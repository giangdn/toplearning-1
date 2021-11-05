<?php

namespace Modules\User\Console;

use App\User;
use Illuminate\Console\Command;
use Modules\ReportNew\Entities\BC15;
use Modules\User\Entities\UserCompletedSubject;

class UserUpdate extends Command
{

    protected $signature = 'user:update';

    protected $description = 'Những thông tin liên quan học viên chạy vào lúc 03h sáng (0 3 * * * )';
    protected $expression = '0 3 * * *';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
//        $users = User::where('auth','=', 'manual')->where('id', '>', 2)->get();
//        foreach ($users as $user){
//            $user->username = str_replace('@hoasengroup.vn', '', $user->username);
//            $user->auth = 'ldap';
//            $user->save();
//        }
        $this->syncReportBC15();
    }
    private function syncReportBC15(){
        $users =\DB::table('el_report_bc15')
            ->select('user_id','subject')
            ->where(['mark'=>1])->get();
        foreach ($users as $index_user => $user) {
            $subjects = json_decode($user->subject,true);
            foreach ($subjects as $index_subject => $subject) {
                $subject_id = $subject['id']??null;
                $subject_completed = UserCompletedSubject::where(['user_id'=>$user->user_id,'subject_id'=>$subject_id])->value('process_type');
                if ($subject_completed){
                    $subjects[$index_subject]['type']=$subject_completed;
                    $sj = collect($subjects)->toJson();
                    BC15::where(['user_id'=>$user->user_id])->update(['subject'=>$sj,'mark'=>0]);
                }
            }
        }
    }
}
