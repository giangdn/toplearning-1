<?php

namespace Modules\ReportNew\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ExportReportNew extends Command
{
    protected $signature = 'report:export_new';

    protected $description = 'Xuất báo cáo mới 1 phút chạy 1 lần';
    protected $expression ="* * * * *";
    protected $max_process = 10;

    public function __construct() {
        parent::__construct();
    }

    public function handle() {

        $count_process = \DB::table('el_history_export_new')->where('status', '=', 3)->count();

        if ($count_process >= $this->max_process) {
            return;
        }

        $exports = \DB::table('el_history_export_new')->where('status', '=', 2)
            ->limit(1)
            ->get();

        foreach ($exports as $key => $export){
            if ($export->class_name == 'User'){
                $class_name = "App\Exports\\". $export->class_name . 'Export';
            }else{
                $class_name = "Modules\ReportNew\Export\\". $export->class_name . 'Export';
            }

            if (!class_exists($class_name)) {
                \DB::table('el_history_export_new')
                    ->where('id', '=', $export->id)
                    ->update([
                        'status' => 0,
                        'error' => 'Class not exists.',
                    ]);

                continue;
            }

            $request = json_decode($export->request);
            $file_name = 'exports_new/'. date('Y/m/d') .'/report_'. $export->class_name .'_'. date('H-i-s') .'_' . $export->id .'.xlsx';

            \DB::table('el_history_export_new')
                ->where('id', '=', $export->id)
                ->update([
                    'status' => 3,
                    'file_name' => $file_name,
                ]);

            try {

                $report = new $class_name($request);
                $report->store($file_name);

                \DB::table('el_history_export_new')
                    ->where('id', '=', $export->id)
                    ->update([
                        'status' => 1,
                    ]);
            }
            catch (\Exception $exception) {
                \DB::table('el_history_export_new')
                    ->where('id', '=', $export->id)
                    ->update([
                        'status' => 0,
                        'error' => $exception->getMessage(),
                    ]);
            }
        }

    }
}
