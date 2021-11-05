<?php

namespace Modules\API\Console;

use App\Certificate;
use App\Models\Categories\Absent;
use App\Models\Categories\District;
use App\Models\Categories\Province;
use App\Models\Categories\TitleRank;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\ProfileStatus;
use Illuminate\Console\Command;
use Modules\API\Entities\API;
use Modules\User\Entities\ProfileLevel;
use Modules\User\Entities\User;
use Modules\User\Entities\WorkingProcess;

class ApiSync extends Command
{

    protected $signature = 'api:sync';

    protected $description = 'API đồng bộ dữ liệu từ nhân sự chạy lúc 02h sáng (0 2 * * * )';
    protected $expression = '0 2 * * *';
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
        $apiUrl = API::orderBy('order')->get();
        foreach ($apiUrl as $index => $item) {
            API::find($item->id)->update(['error'=>1,'start_time'=>now()]);
            if ($item->id==1) // loại nghỉ
                Absent::syncAPIAbsent($item->url,$item->code);
            elseif($item->id==2) // cấp bậc chức danh
                TitleRank::syncAPITitleRank($item->url,$item->code);
            elseif($item->id==3) // chức danh
                Titles::syncAPITitle($item->url,$item->code);
            elseif($item->id==4) // tỉnh thành
                Province::syncAPIProvince($item->url,$item->code);
            elseif($item->id==5) // trình độ
                Certificate::syncAPICertificate($item->url,$item->code);
            elseif($item->id==6) // đơn vị
                Unit::syncAPIUnit($item->url,$item->code);
            elseif ($item->id==7)// nhân viên
                User::syncAPIUser($item->url,$item->code);
            elseif ($item->id==8)// Quá trình công tác
                WorkingProcess::syncAPIWorkingProcess($item->url,$item->code);
            API::find($item->id)->update(['error'=>0,'end_time'=>now()]);
        }
        $this->info('Cập nhật thành công');
    }
}
