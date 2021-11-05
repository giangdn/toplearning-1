<?php
namespace Modules\Capabilities\Imports;

use App\Models\Categories\Titles;
use Modules\Capabilities\Entities\Capabilities;
use Modules\Capabilities\Entities\CapabilitiesTitle;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CapabilitiesTitleImport implements ToModel, WithStartRow
{
    public $errors;
    public function __construct()
    {
        $this->errors = [];
    }

    public function model(array $row)
    {
        $error = false;
        $number_title = $row[0];
        $title_code = $row[1];
        $capabilities_code = $row[2];
        $level = (int) $row[5];
        $weight = (int) $row[3];
        $critical_level = (int) $row[4];

        $title = Titles::where('code', '=', $title_code)->first();
        if (empty($title)) {
            $this->errors[] = 'Mã chức danh <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if ($title){
            $total_weight = CapabilitiesTitle::checkWeight($title->id);

            if ($total_weight == 100){
                $this->errors[] = 'Chức danh <b>'. $title->name .'</b> không thể thêm được nữa';
                $error = true;
            }

            if (($total_weight + $weight) > 100){
                $this->errors[] = 'Trọng số chức danh <b>'. $title->name .'</b> chỉ còn thêm được ' . (100 - $total_weight) . '%';
                $error = true;
            }

            if(CapabilitiesTitle::checkNumber($title->id, $number_title)){
                $this->errors[] = 'STT '. $number_title .' thuộc chức danh '. $title->name .' đã tồn tại';
                $error = true;
            }
        }

        $capabilities = Capabilities::where('code', '=', $capabilities_code)->first();

        if ($title && $capabilities){
            if(CapabilitiesTitle::checkExists($capabilities->id, $title->id)){
                $this->errors[] = 'Năng lực '. $capabilities->name . ' thuộc chức danh '. $title->name .' đã tồn tại';
                $error = true;
            }
        }

        if (empty($capabilities)) {
            $this->errors[] = 'Mã năng lực <b>'. $row[2] .'</b> không tồn tại';
            $error = true;
        }


        if (!in_array($level, [1, 2, 3, 4])) {
            $this->errors[] = 'Cấp độ không tồn tại';
            $error = true;
        }

        if ($weight <= 0 || $weight > 100) {
            $this->errors[] = 'Trọng số phải lớn hơn 0 và nhỏ hơn 100';
            $error = true;
        }

        if($error) {
            return null;
        }
        
        CapabilitiesTitle::create([
            'number_title' => $number_title,
            'capabilities_id' =>(int) $capabilities->id,
            'title_id' => (int) $title->id,
            'weight' => $weight,
            'critical_level' => $critical_level,
            'level' => $level,
            'goal' => (($critical_level * $level) * $weight/100),
        ]);

        $total_weight_capa_cate = Capabilities::getTotalWeightByTitleGroup($title->id, $capabilities->category_id);
        $capa_title_group = Capabilities::getByTitleGroup($title->id, $capabilities->category_id);
        foreach ($capa_title_group as $item){
            $capa_title = CapabilitiesTitle::where('title_id', '=', $title->id)
                ->where('capabilities_id', '=', $item->capabilities_id)
                ->first();

            $capa_title->goal = CapabilitiesTitle::getGoal($capa_title->level, $capa_title->critical_level, $total_weight_capa_cate);
            $capa_title->save();
        }
    }
    
    public function startRow(): int
    {
        return 2;
    }

}