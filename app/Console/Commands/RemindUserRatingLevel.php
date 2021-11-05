<?php

namespace App\Console\Commands;

use App\Automail;
use App\PlanApp;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Notify\Entities\Notify;
use Modules\Notify\Entities\NotifyTemplate;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRatingLevelObject;
use Modules\Online\Entities\OnlineRegister;
use Modules\Rating\Entities\RatingLevelCourse;

class RemindUserRatingLevel extends Command
{
    protected $signature = 'mail:remind_user_rating_level';

    protected $description = 'Thư Nhắc thực hiện Đánh giá hiệu quả đào tạo. Đối tượng nhận: Người chưa gửi đánh giá. Thời gian gửi: trước lúc kết thúc đánh giá 1 ngày nhưng học viên chưa đánh giá. chạy cron lúc 01h sáng (0 1 * * *)';

    protected $expression ='0 1 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = now();
        $today1day = strtotime(date("Y-m-d", strtotime($today)) . " +1 day");
        $today1day = strftime("%Y-%m-%d 23:59:59", $today1day);

        $prefix = DB::getTablePrefix();
        $template_code = 'action_plan_reminder_02';
        $ctypes = ['online', 'offline', 'course'];

        foreach ($ctypes as $ctype) {
            $object_type = 'action_plan_reminder_'. $ctype.'_02';
            if ($ctype == 'online'){
                $course_type = 1;
                $table_register = 'el_online_register_view';
            }
            if ($ctype == 'offline'){
                $course_type = 2;
                $table_register = 'el_offline_register_view';
            }
            if ($ctype == 'course'){
                $course_type = 3;
                $table_register = 'el_rating_levels_register';
            }


            $query = DB::query();
            $query->select([
                'a.*',
            ]);
            $query->from('el_'.$ctype.'_rating_level_object as a');
            $query->where('a.time_type', '=', 1);
            $query->whereNotNull('a.end_date');
            $query->where('a.end_date', '>', now());
            $query->where('a.end_date', '=', $today1day);
            $rows = $query->get();
            foreach ($rows as $row){
                $rating_level = DB::table('el_'.$ctype.'_rating_level')->find($row->{$ctype.'_rating_level_id'});
                if ($course_type == 3){
                    $course_id = $row->rating_levels_id;
                }else{
                    $course_id = $row->course_id;
                }
                $user_rating_level = RatingLevelCourse::query()
                    ->where('course_rating_level_id', '=', $rating_level->id)
                    ->where('course_id', '=', $course_id)
                    ->where('course_type', '=', $course_type)
                    ->where('level', '=', $rating_level->level)
                    ->where('send', '=', 1)
                    ->pluck('user_id')->toArray();

                if ($row->object_type == 1){
                    $user_arr = DB::table($table_register);
                    if ($course_type == 3){
                        $user_arr->where('rating_levels_id', $course_id);
                    }else{
                        $user_arr->where('course_id', $course_id);
                    }
                    $user_arr = $user_arr->whereNotIn('user_id', $user_rating_level)
                        ->pluck('user_id')->toArray();
                    if (count($user_arr) == 0){
                        continue;
                    }
                    foreach ($user_arr as $item){
                        $user = Profile::whereUserId($item)->first();
                        $signature = getMailSignature($user->user_id);
                        $params = [
                            'signature' => $signature,
                            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                            'full_name' => $user->lastname.' '.$user->firstname,
                            'rating_name' => $rating_level->rating_name,
                            'url' => route('module.rating_level'),
                        ];

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $course_id, $object_type, $template_code);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id], $template_code);
                    }
                }
                if ($row->object_type == 2){
                    $user_arr = DB::query()
                        ->select(['b.user_code'])
                        ->from($table_register.' as a')
                        ->leftJoin('el_unit_manager as b', 'b.unit_code', '=', 'a.unit_code')
                        ->leftJoin('el_profile as c', 'c.code', '=', 'b.user_code')
                        ->whereNotIn('c.user_id', $user_rating_level);
                    if ($course_type == 3){
                        $user_arr->where('a.rating_levels_id', $course_id);
                    }else{
                        $user_arr->where('a.course_id', $course_id);
                    }
                    $user_arr = $user_arr->groupBy('b.user_code')
                        ->pluck('b.user_code')->toArray();
                    if (count($user_arr) == 0){
                        continue;
                    }
                    foreach ($user_arr as $item){
                        $user = Profile::whereCode($item)->first();
                        $signature = getMailSignature($user->user_id);
                        $params = [
                            'signature' => $signature,
                            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                            'full_name' => $user->lastname.' '.$user->firstname,
                            'rating_name' => $rating_level->rating_name,
                            'url' => route('module.rating_level'),
                        ];

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $course_id, $object_type, $template_code);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id], $template_code);
                    }
                }
                if ($row->object_type == 3){
                    $user_arr = DB::table('el_'.$ctype.'_rating_level_object_colleague')
                        ->where($ctype.'_rating_level_id', '=', $rating_level->id)
                        ->whereNotIn('user_id', $user_rating_level)
                        ->pluck('user_id')->toArray();
                    if (count($user_arr) == 0){
                        continue;
                    }
                    foreach ($user_arr as $item){
                        $user = Profile::whereUserId($item)->first();
                        $signature = getMailSignature($user->user_id);
                        $params = [
                            'signature' => $signature,
                            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                            'full_name' => $user->lastname.' '.$user->firstname,
                            'rating_name' => $rating_level->rating_name,
                            'url' => route('module.rating_level'),
                        ];

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $course_id, $object_type, $template_code);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id], $template_code);
                    }
                }
                if ($row->object_type == 4 && isset($row->user_id[4])){
                    $user_arr = array_diff($row->user_id[4], $user_rating_level);
                    if (count($user_arr) == 0){
                        continue;
                    }
                    foreach ($user_arr as $item){
                        $user = Profile::whereUserId($item)->first();
                        $signature = getMailSignature($user->user_id);
                        $params = [
                            'signature' => $signature,
                            'gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                            'full_name' => $user->lastname.' '.$user->firstname,
                            'rating_name' => $rating_level->rating_name,
                            'url' => route('module.rating_level'),
                        ];

                        $this->updateMailUserRatingLevel($params, [$user->user_id], $course_id, $object_type, $template_code);
                        $this->updateNotifyUserRatingLevel($params, [$user->user_id], $template_code);
                    }
                }
            }
        }
    }

    protected function updateMailUserRatingLevel(array $params, array $user_id, int $object_id, $object_type, $template_code){
        $automail = new Automail();
        $automail->template_code = $template_code;
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $object_id;
        $automail->object_type = $object_type;
        $automail->addToAutomail();
    }

    protected function updateNotifyUserRatingLevel(array $params, array $user_id, $template_code){
        $nottify_template = NotifyTemplate::query()->where('code', '=', $template_code)->first();
        $subject_notify = $this->mapParams($nottify_template->title, $params);
        $content_notify = $this->mapParams($nottify_template->content, $params);
        $url = $this->getParams($params, 'url');

        $notify = new Notify();
        $notify->subject = $subject_notify;
        $notify->content = $content_notify;
        $notify->url = $url;
        $notify->users = $user_id;
        $notify->addMultiNotify();
    }

    protected function mapParams($content, $params) {
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

    protected function getParams($params, $key) {
        if (isset($params->{$key})) {
            return $params->{$key};
        }

        return null;
    }
}
