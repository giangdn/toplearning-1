<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Categories\UnitType;
use App\Models\Categories\UnitTypeCode;
use App\Models\Categories\Unit;

class CronUnitTypeCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add_unit_type_code';
    protected $description = 'Thêm loại đơn vị cho từng đơn vị chạy 23h hàng ngày (0 23 * * *)';
    protected $expression ='0 23 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $units = Unit::get();
        $units_type_code = UnitTypeCode::get();
        foreach ($units as $key => $unit) {
            if( !empty($units_type_code)) {
                $substr_compare = 0;
                foreach($units_type_code as $unit_type_code) {
                    $str_unit_type_code = strlen(trim($unit_type_code->code));
                    $check_substr_compare = substr_compare(trim($unit_type_code->code), trim($unit->code), 0, $str_unit_type_code);
                    if($check_substr_compare == 0) {
                        Unit::where(['id'=> $unit->id])->update(['type'=>(int) $unit_type_code->unit_type_id]);
                        $substr_compare = 1;
                    } 
                }
                if ($substr_compare == 0) {
                    Unit::where(['id'=> $unit->id])->update(['type'=>2]);
                }
            } else {
                Unit::where(['id'=> $unit->id])->update(['type'=>2]);
            }
        }
        $this->info('Cập nhật thành công');
    }
}
