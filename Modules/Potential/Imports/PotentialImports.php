<?php
namespace Modules\Potential\Imports;

use Modules\Potential\Entities\Potential;
use App\Profile;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PotentialImports implements ToModel, WithStartRow
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
        $start_date = date_convert($row[3]);
        $end_date = date_convert($row[4], '23:59:59');

        $profile = Profile::where('code', '=', $code)->first();

        $exists1 = Potential::where('user_id', '=', $profile->user_id)
                ->where('start_date', '<=', $start_date)
                ->where('end_date' , '>=', $start_date)
                ->exists();

        $exists2 = Potential::where('user_id', '=', $profile->user_id)
                ->where('start_date', '<=', $end_date)
                ->where('end_date' , '>=', $end_date)
                ->exists();

        if (empty($profile)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if ($exists1 || $exists2) {
            $this->errors[] = 'Thời gian của <b>'. $profile->lastname . ' ' . $profile->firstname .'</b> đã tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        Potential::create([
            'user_id' => $profile->user_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }
    
    public function startRow(): int
    {
        return 2;
    }

}