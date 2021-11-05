<?php
namespace Modules\Libraries\Imports;

use Modules\Libraries\Entities\Libraries;
use Modules\Libraries\Entities\LibrariesObject;
use App\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProfileImport implements ToModel, WithStartRow
{
    public $errors;
    public $libraries_id;

    public function __construct($libraries_id)
    {
        $this->errors = [];
        $this->libraries_id = $libraries_id;
    }

    public function model(array $row)
    {
        $error = false;
        $user_code = $row[1];
        $status = $row[2];

        $profile = Profile::where('code', '=', $user_code)->first();

        if($profile){
            $libraries = LibrariesObject::where('user_id', '=', $profile->user_id)
            ->where('libraries_id', '=', $this->libraries_id)->first();

            if ($libraries) {
                $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã được thêm';
                $error = true;
            }
        }

        if (!in_array($status, [1,2,3])){
            $this->errors[] = 'Quyền không đúng';
            $error = true;
        }

        if (empty($profile)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        $lib = Libraries::find($this->libraries_id);

        LibrariesObject::create([
            'user_id' =>(int) $profile->user_id,
            'libraries_id' => $this->libraries_id,
            'status' => $lib->type == 4 ? 1 : ($status ? $status : 3),
            'type' => $lib->type,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
