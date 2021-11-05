<?php
namespace App\Imports;

use App\EmulationProgram;
use App\EmulationProgramObject;
use App\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EmulationUserImport implements ToModel, WithStartRow
{
    public $errors;
    public $emulation_id;

    public function __construct($emulation_id)
    {
        $this->errors = [];
        $this->emulation_id = $emulation_id;
    }

    public function model(array $row)
    {
        $error = false;
        $user_code = $row[1];

        $profile = Profile::where('code', '=', $user_code)->first();

        if($profile){
            $emulation_object = EmulationProgramObject::where('user_id', '=', $profile->user_id)
            ->where('emulation_id', '=', $this->emulation_id)->first();

            if ($emulation_object) {
                $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã được thêm';
                $error = true;
            }
        }

        if (empty($profile)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        EmulationProgramObject::create([
            'user_id' =>(int) $profile->user_id,
            'emulation_id' => $this->emulation_id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
