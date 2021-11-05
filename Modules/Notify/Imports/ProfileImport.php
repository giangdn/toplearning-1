<?php
namespace Modules\Notify\Imports;

use Modules\Notify\Entities\NotifySendObject;
use App\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProfileImport implements ToModel, WithStartRow
{
    public $errors;
    public $notify_send_id;

    public function __construct($notify_send_id)
    {
        $this->errors = [];
        $this->notify_send_id = $notify_send_id;
    }

    public function model(array $row)
    {
        $error = false;
        $user_code = $row[1];

        $profile = Profile::where('code', '=', $user_code)->first();

        if($profile){
            $notify_send = NotifySendObject::where('user_id', '=', $profile->user_id)
            ->where('notify_send_id', '=', $this->notify_send_id)->first();
            
            if ($notify_send) {
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
        
        NotifySendObject::create([
            'user_id' =>(int) $profile->user_id,
            'notify_send_id' => $this->notify_send_id,
        ]);
    }
    
    public function startRow(): int
    {
        return 2;
    }

}