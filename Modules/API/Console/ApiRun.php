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

class ApiRun extends Command
{

    protected $signature = 'api:run {param}';

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

        $id = (int) $this->argument('param');
        $api = API::where('id',$id)->firstOrFail();
        API::find($id)->update(['error'=>1,'start_time'=>now(),'end_time'=>null]);
        if($id==1) // loại nghỉ
            Absent::syncAPIAbsent($api->url,$api->code);
        elseif($id==2) // cấp bậc chức danh
            TitleRank::syncAPITitleRank($api->url,$api->code);
        elseif($id==3) // chức danh
            Titles::syncAPITitle($api->url,$api->code);
        elseif($id==4) // tỉnh thành
            Province::syncAPIProvince($api->url,$api->code);
        elseif($id==5) // trình độ
            Certificate::syncAPICertificate($api->url,$api->code);
        elseif($id==6) // đơn vị
            Unit::syncAPIUnit($api->url,$api->code);
        elseif($id==7) // nhân viên
            User::syncAPIUser($api->url,$api->code);
        elseif($id==8) // Quá trình công tác
            WorkingProcess::syncAPIWorkingProcess($api->url,$api->code);
        API::find($id)->update(['error'=>0,'end_time'=>now()]);

        $this->info('Cập nhật thành công');
    }
}
