<?php
namespace Modules\Permission\Imports;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Modules\Permission\Entities\UnitManagerSetting;

class UnitManagerImport implements ToCollection, WithStartRow
{
    use Importable;
    public $imported_by;
    public $errors;
    public function __construct(User $user)
    {
        $this->errors = [];
        $this->imported_by = $user;
    }
    public function collection(Collection $collections)
    {

        foreach ($collections as $index => $item) {
            $unit_code = trim($item[0]);
            $priority1 = trim($item[2])?array_map('trim',explode(',',$item[2])):[];
            $priority2 = trim($item[3])?array_map('trim',explode(',',$item[3])):[];
            $priority3 = trim($item[4])?array_map('trim',explode(',',$item[4])):[];
            $priority4 = trim($item[5])?array_map('trim',explode(',',$item[5])):[];
            $unit = Unit::where('code','=',$unit_code)->first();

            if (!$unit){
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột mã đơn vị] không hợp lệ';
                continue;
            }
            if (!$priority1){
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột chức danh ưu tiên 1] không được để trống';
                continue;
            }

            $title1 = Titles::whereIn('code',$priority1)->where(['status'=>1])->get('id');

            if ($title1->count() < count($priority1)){
                $this->errors[] = '<b> Dòng '. ($index+1) .'</b>: [Cột chức danh ưu tiên 1] không hợp lệ';
                continue;
            }
            $title2 = Titles::whereIn('code',$priority2)->where(['status'=>1])->get('id');
            $title3 = Titles::whereIn('code',$priority3)->where(['status'=>1])->get('id');
            $title4 = Titles::whereIn('code',$priority4)->where(['status'=>1])->get('id');

            $model = UnitManagerSetting::firstOrNew(['unit_id'=>$unit->id]);
            $model->unit_id =$unit->id;
            $model->priority1 = $title1?json_encode($title1->pluck('id')):null;
            $model->priority2 = $title2?json_encode($title2->pluck('id')):null;
            $model->priority3 = $title3?json_encode($title3->pluck('id')):null;
            $model->priority4 = $title4?json_encode($title4->pluck('id')):null;
            $model->save();
        }
    }
    public function startRow(): int
    {
        return 3;
    }
    public function chunkSize(): int
    {
        return 200;
    }
}
