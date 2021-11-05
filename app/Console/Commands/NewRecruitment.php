<?php

namespace App\Console\Commands;

use App\Models\Categories\Titles;
use Illuminate\Console\Command;

class NewRecruitment extends Command
{
    protected $signature = 'command:new_recruitment';

    protected $description = 'Đổ nhân sự từ profile qua tân quyển, bắt đầu từ ngày vào làm đến ngày setup trong setting tân tuyển';
    protected $hidden = true;
    public function __construct()
    {
        parent::__construct();
    }

    /*
     *
     * */
    public function handle()
    {
        $query = \DB::query();
        $query->select([
            'profile.id',
            'profile.user_id',
            'profile.title_code',
            'setting.max_date',
            'setting.probation',
            'setting.form_evaluate',
            'profile.join_company'
        ])
            ->from('el_profile AS profile')
            ->join('el_titles AS title', 'title.code', '=', 'profile.title_code')
            ->join('el_new_recruitment_title_setting AS setting', 'setting.title_id', '=', 'title.id')
            ->whereNotNull('profile.join_company')
            ->whereNotNull('setting.max_date')
            ->where('profile.join_company', '!=', '')
            ->where('setting.max_date', '!=', '')
            ->where('join_company', '>=', \DB::raw("DATEADD(day,- mdl_setting.max_date,CONVERT(VARCHAR(10), getdate(), 111))"))
            ->where('join_company', '<=', date('Y-m-d 23:59:59'))
            ->whereNotIn('user_id', function ($subquery) {
                $subquery->select(['user_id'])
                    ->from('el_new_recruitment');
            });

        if (!$query->exists()) {
            echo 'ok';
            exit();
        }

        $rows = $query->get();
        foreach ($rows as $row) {
            $plugday = strtotime(date("Y-m-d", strtotime($row->join_company)) . " +{$row->probation} day");
            $plugday = strftime("%Y-%m-%d 23:59:59", $plugday);

            $model = \Modules\NewRecruitment\Entities\NewRecruitment::firstOrNew(['user_id' => $row->user_id]);
            $model->user_id = $row->user_id;
            $model->probation = $row->probation;
            $model->start_date = $row->join_company;
            $model->end_date = $plugday;
            $model->form_evaluate = $row->form_evaluate;
            $model->save();
        }
    }
}
