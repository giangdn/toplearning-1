<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Profile;
use App\ProfileView;
use Modules\Libraries\Entities\RegisterBook;
use Carbon\Carbon;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\Notify;
use Illuminate\Support\Str;
use App\Automail;

class CheckRegisterBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_register_book';
    protected $description = 'Kiểm tra đến hạn trả, quá hạn trả sách và tạo mail đến user chạy lúc 2h (0 2 * * *)';
    protected $expression ='0 2 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $models = RegisterBook::query()
            ->select([
                'a.*',
                'b.name as book_name',
            ])
            ->from('el_register_book as a')
            ->leftjoin('el_libraries as b','b.id','=','a.book_id')
            ->where('a.status','=',2)
            ->where('a.approved',1)
            ->whereNotNull('a.pay_date')
            ->get();
        if (!$models->isEmpty()) {
            foreach($models as $model) {
                $profile = ProfileView::where('user_id',$model->user_id)->first();

                $get_pay_date = Carbon::parse($model->pay_date)->format('Y-m-d');
                $format_pay_date = Carbon::parse($model->pay_date)->format('d-m-Y H:i');
                $time = strtotime($get_pay_date);
                $date_time = date('Y-m-d');
                $before_pay_date = date("Y-m-d", strtotime("-1 day", $time));
                if ($date_time == $before_pay_date) {
                    $query = new Notify();
                    $query->user_id = $model->user_id;
                    $query->subject = 'Đến hạn trả sách';
                    $query->content = 'Sách '.$model->book_name.' cần được trả vào ngày '.$model->pay_date.' Vui lòng liên hệ người quản lý sách để trả';
                    $query->url = '';
                    $query->created_by = 0;
                    $query->save();

                    $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
                    $redirect_url = route('module.notify.view', [
                        'id' => $model->id,
                        'type' => 1
                    ]);

                    $notification = new AppNotification();
                    $notification->setTitle($query->subject);
                    $notification->setMessage($content);
                    $notification->setUrl($redirect_url);
                    $notification->add($model->user_id);
                    $notification->save();

                    $signature = getMailSignature($profile->user_id);
                    $automail = new Automail();
                    $automail->template_code = 'pay_date_register_book';
                    $automail->params = [
                        'signature' => $signature,
                        'gender' => $profile->gender=='1'?'Anh':'Chị',
                        'full_name' => $profile->full_name,
                        'book_name' => $model->book_name,
                        'pay_date' => $format_pay_date,
                    ];
                    $automail->users = [$profile->user_id];
                    $automail->check_exists = true;
                    $automail->object_id = $model->book_id;
                    $automail->check_exists_status = 0;
                    $automail->object_type = 'pay_date_register_book';
                    $automail->addToAutomail();

                } else if ($date_time > $get_pay_date) {
                    $query = new Notify();
                    $query->user_id = $model->user_id;
                    $query->subject = 'Qúa hạn mượn sách';
                    $query->content = 'Sách '.$model->book_name.' được mượn bởi '.$model->lastname . $model->firstname. '-' .$model->user_code.' đã quá hạn mượn vào ngày '. $model->pay_date;
                    $query->url = '';
                    $query->created_by = 0;
                    $query->save();

                    $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
                    $redirect_url = route('module.notify.view', [
                        'id' => $query->id,
                        'type' => 1
                    ]);

                    $notification = new AppNotification();
                    $notification->setTitle($query->subject);
                    $notification->setMessage($content);
                    $notification->setUrl($redirect_url);
                    $notification->add($model->user_id);
                    $notification->save();

                    $signature = getMailSignature($profile->user_id);
                    $automail = new Automail();
                    $automail->template_code = 'out_of_date_register_book';
                    $automail->params = [
                        'signature' => $signature,
                        'gender' => $profile->gender=='1'?'Anh':'Chị',
                        'full_name' => $profile->full_name,
                        'book_name' => $model->book_name,
                        'pay_date' => $format_pay_date,
                    ];
                    $automail->users = [$profile->user_id];
                    $automail->check_exists = true;
                    $automail->object_id = $model->book_id;
                    $automail->check_exists_status = 0;
                    $automail->object_type = 'out_of_date_register_book';
                    $automail->addToAutomail();
                }
            }
        }
    }
}
