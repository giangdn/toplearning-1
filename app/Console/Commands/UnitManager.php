<?php

namespace App\Console\Commands;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Console\Command;
use Modules\Permission\Entities\UnitManagerSetting;
use Symfony\Polyfill\Intl\Idn\Info;

class UnitManager extends Command
{
    protected $signature = 'command:unitmanager';

    protected $description = 'update trưởng đơn vị 1 ngày chạy 1 lần lúc 3h sáng (0 3 * * *)';
    protected $expression = '0 3 * * *';
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
        $data = \DB::table('el_unit_manager_setting as a')->join('el_unit as b','a.unit_id','b.id')
            ->select('a.unit_id','a.priority1','a.priority2','a.priority3','a.priority4','b.code as unit_code')
            ->get();
        if ($data->isEmpty())
            return;
        foreach ($data as $item){
            $users = $this->getUserInternalUnitbyTitle($item->unit_id,$item->priority1,$item->priority2,$item->priority3,$item->priority4);
            if (!$users)
                continue;
            foreach ($users as $index => $user) {
                $unitManager = \App\Models\Categories\UnitManager::updateOrInsert(['unit_code'=>$item->unit_code,'user_code'=>$user->code],
                    ['type'=>1,'manager_type'=>1]);
            }
        }
        \Log::info('Chạy cron cập nhật trưởng đơn vị thành công');
    }
    private function getUserInternalUnitbyTitle($unit_id,$priority1,$priority2,$priority3,$priority4 ){
        $unitCode = Unit::findOrFail($unit_id)->code;
        for($i=1;$i<=4;$i++){
            $priority = ${'priority'.$i};
            if ($priority) {
                $title = json_decode($priority);
                $profile = Profile::whereIn('title_id', $title)->where(['unit_id' => $unit_id])->select('code')->get();
                if ($profile->isNotEmpty()){
                    return $profile;
                    break;
                }
                $unitChild = Unit::getArrayChild($unitCode);
                $profile = Profile::whereIn('title_id', $title)->whereIn('unit_id',$unitChild)->select('code')->get();
                if ($profile->isNotEmpty()){
                    return $profile;
                    break;
                }
            }
        }
        return false;
    }
}
