<?php

namespace App\Console\Commands;

use App\MailTemplate;
use App\Models\MailHistory;
use App\Profile;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Modules\Notify\Entities\Notify;
use App\Automail as AutoModel;
use Modules\Notify\Entities\NotifyTemplate;
use App\CourseView;

class AutoMail extends Command
{
    protected $signature = 'mail:auto';

    protected $description = 'Gửi mail tự động 1 phút chạy 1 lần (* * * * *)';

    protected $expression ='* * * * *';
    public function __construct() {
        parent::__construct();
    }

    public function mapParams($content, $params) {
        // $content = '';
        $params = json_decode($params);
        foreach ($params as $key => $param) {
            if ($key == 'url') {
                $content = str_replace('{'. $key .'}', '<a target="_blank" href="'. $param .'">liên kết này</a>', $content);
            }
            else {
                $content = str_replace('{'. $key .'}', $param, $content);
            }
        }
        return $content;
    }

    public function getParams($params, $key) {
        $params = json_decode($params);
        if (isset($params->{$key})) {
            return $params->{$key};
        }

        return null;
    }

    public function handle() {
        $hasMailServer = false;
        if ($this->checkEmailConfig()) {
            $hasMailServer = true;
        }

        $query = \DB::query();
        $query->select([
            'a.id',
            'a.list_mail',
            'a.params',
            'a.template_code',
            'a.object_id',
            'b.title',
            'b.content'
        ]);
        $query->from('el_automail AS a')
            ->join('el_mail_template AS b', 'b.code', '=', 'a.template_code')
            ->where('a.status', '=', 0)
            ->where('b.status', '=', 1)
            ->where('a.sendtime', '<', date('Y-m-d H:i:s'))
            ->orderBy('priority', 'DESC')
            ->limit(100);

        $rows = $query->get();

        foreach ($rows as $row) {
            $email = AutoModel::find($row->id);

            $params = json_decode($row->params);
            $get_course_offline = CourseView::where('course_type',2)->pluck('code')->toArray();
            $get_course_online = CourseView::where('course_type',1)->pluck('code')->toArray();

            $emails = explode(',', $row->list_mail);
            $subject = $this->mapParams($row->title, $row->params);
            $content = $this->mapParams($row->content, $row->params);
            $url = $this->getParams($row->params, 'url');

            if( (isset($params->course_code) && (in_array($params->course_code, $get_course_offline) || in_array($params->course_code, $get_course_online))) || isset($params->quiz_type) || isset($params->book_name) ) {
                if( !isset($params->quiz_type) && !isset($params->book_name) ) {
                    /* thông báo */
                    $nottify_template = NotifyTemplate::query()->where('code', '=', $row->template_code)->first();
                    $subject_notify = $this->mapParams($nottify_template->title, $row->params);
                    $content_notify = $this->mapParams($nottify_template->content, $row->params);

                    $notify = new Notify();
                    $notify->subject = $subject_notify;
                    $notify->content = $content_notify;
                    $notify->url = $url;
                    $notify->users = Profile::whereIn('email', $emails)->pluck('user_id')->toArray();
                    $notify->addMultiNotify();
                }
            }

            try {
                $emailTemplate = MailTemplate::where('code', $email->template_code)->first();

                $mailHistory = new MailHistory();
                $mailHistory->code = $email->template_code;
                $mailHistory->name = empty($emailTemplate) ? null : $emailTemplate->name;
                $mailHistory->content = empty($emailTemplate) ? null : $emailTemplate->content;
                $mailHistory->list_mail = $email->list_mail;
                $mailHistory->params = $email->params;
                $mailHistory->send_time = date('Y-m-d H:i:s');
                if ( empty($emailTemplate) ){
                    $mailHistory->status=2;
                    $mailHistory->error = 'Chưa có mail template '.$email->template_code;
                }
                elseif($hasMailServer) {
                    Mail::send('mail.default', [
                        'content' => $content
                    ], function ($message) use ($emails, $subject) {
                        $message->to($emails)->subject($subject);
                    });
                    if (Mail::failures()) {
                        \Log::error(implode(',', Mail::failures()));
                        $mailHistory->error = implode(',', Mail::failures());
                        $mailHistory->status = 2;
                    }
                    else{
                        $mailHistory->status = 1;

                        if ($row->template_code == 'reset_pass'){
                            $pass = $this->getParams($row->params, 'pass');

                            $user = User::where('id', '=', $row->object_id)->where('id', '>', 2)->first();
                            $user->password = password_hash($pass, PASSWORD_DEFAULT);
                            $user->save();
                        }
                    }

                }else{
                    $mailHistory->status=2;
                    $mailHistory->error = 'Chưa cấu hình mail server';
                }
                $mailHistory->save();
                $email->delete();
            }
            catch(\Exception $e){
                \Log::error($e->getMessage());
                continue;
            }
        }
    }

    protected function checkEmailConfig() {
        $mail = \App\Config::getConfigEmail();
        if (empty($mail['email_driver'])) {
            return false;
        }

        if (empty($mail['email_host'])) {
            return false;
        }

        if (empty($mail['email_port'])) {
            return false;
        }

        if (empty($mail['email_user'])) {
            return false;
        }

        return true;
    }
}
