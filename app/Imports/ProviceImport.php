<?php
namespace App\Imports;
use App\Models\Categories\District;
use App\Models\Categories\Province;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProviceImport implements ToModel, WithStartRow
{
    public $errors;
    protected $plat = 0;
    protected $id_province = 0;
    protected $id_district = 0;

    public function __construct()
    {
        $this->errors = [];
    }

    public function model(array $row)
    {
        $error = false;
        $index = (int) $row[0];
        if($index){
            $this->plat = 0;
            $province = Province::where('name', '=', $row[1])->first();
            if ($province){
                $this->errors[] = 'Tỉnh thành số <b>'. $index .'</b> đã tồn tại';
                $error = true;
                $this->plat = 1;
            }
        }
        if($error) {
            return null;
        }

        if($index){
            $this->id_province++;
            Province::create([
                'id' => (int)$this->id_province,
                'name' => $row[1],
            ]);
        }else{
            if ($this->plat == 0){
                $province = Province::orderBy('id', 'DESC')->get();
                // echo "<pre>";
                // print_r($province);die;
                if ($row[1]) {
                    $this->id_district++;
                    District::create([
                        'id' => (int)$this->id_district,
                        'name' => $row[1],
                        'province_id' => $row[2],
                    ]);
                }
            }
        }
    }
    
    public function startRow(): int
    {
        return 2;
    }

}