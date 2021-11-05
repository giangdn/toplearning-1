<?php

namespace App\Console\Commands;

use App\Automail;
use App\Profile;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use Illuminate\Console\Command;

class MailReviewPlanManager extends Command
{

    protected $signature = 'mail:review_plan_manager';

    protected $description = 'Thư Đánh giá Đánh giá hiệu quả đào tạo của nhân viên. Đối tượng nhận: Trưởng đơn vị Thời gian gửi: Sau khi nhân viên thuộc đơn vị gửi Tự đánh giá Đánh giá hiệu quả đào tạo. cron chạy 1h sáng (0 1 * * *)';
    protected $expression ='0 1 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $dbprefix = \DB::getTablePrefix();
        $query = \DB::query();
        $query->select([
            'a.id',
            'a.user_id',
            'a.course_id',
            'b.code',
            'b.name',
            'b.course_type',
            'b.start_date',
            'b.end_date'
        ]);

        $query->from('el_plan_app AS a')
            ->join('el_course_view AS b', function ($subquery) {
                $subquery->on('b.course_id', '=', 'a.course_id');
                $subquery->on('b.course_type', '=', 'a.course_type');
            })
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1);
        $rows = $query->get();

        foreach ($rows as $row) {

            $profile = Profile::find($row->user_id);
            if (empty($profile)) {
                continue;
            }

            $users = UnitManager::getUnitManager($profile->unit_code);

            foreach ($users as $user){
                $signature = getMailSignature($user->user_id);
                $automail = new Automail();
                $automail->template_code = 'review_action_plan_manager';
                $automail->params = [
                    'signature' => $signature,
                    'code' => $row->code,
                    'name' => $row->name,
                    'start_date' => $row->start_date,
                    'type' => $row->course_type == 1 ? 'Trực tuyến' : 'Tập trung',
                    'end_date' => $row->end_date,
                    'url' => route('module.plan_app.user', [
                        'course' => $row->course_id,
                        'type' => $row->course_type
                    ])
                ];

                $automail->users = [$user->user_id];
                $automail->check_exists = true;
                $automail->object_id = $row->id;
                $automail->object_type = 'review_action_plan_manager_'. ($row->course_type == 1 ? 'online' : 'offline');

                if ($automail->addToAutomail()) {
                    echo "mail:review_plan_manager \n";
                }
                else {
                    echo "mail:review_plan_manager not \n";
                }
            }

        }
    }
}
