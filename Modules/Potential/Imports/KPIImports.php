<?php
namespace Modules\Potential\Imports;

use App\KPI;
use App\Profile;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KPIImports implements ToModel, WithStartRow
{
    public $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function model(array $row)
    {
        $error = false;

        $code = $row[1];
        $profile = Profile::where('code', '=', $code)->first();

        if (empty($profile)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        KPI::create([
            'user_code' => $code,
            'year' => $row[2],
            'quarter_1' => $row[3],
            'quarter_2' => $row[4],
            'quarter_3' => $row[5],
            'quarter_4' => $row[6],
            'quarter_year' => $row[7],
        ]);
    }
    
    public function startRow(): int
    {
        return 2;
    }

}