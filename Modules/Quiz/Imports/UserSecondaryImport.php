<?php
namespace Modules\Quiz\Imports;

use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\QuizSettingAlert;
use Modules\Quiz\Entities\QuizUserSecondary;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UserSecondaryImport implements ToModel, WithStartRow
{
    public $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function model(array $row)
    {
        $error = false;
        $user_code = $row[1];
        $username = (string) $row[3];
        $password = (string) $row[4];
        $identity_card = $row[7];

        $code = QuizUserSecondary::where('code', '=', $user_code)->first();
        $user_name = QuizUserSecondary::where('username', '=', 'secondary_'.$username)->first();

        $setting_alert = QuizSettingAlert::query()->first();

        if ($setting_alert){
            $user_second = QuizUserSecondary::query()
                ->where('identity_card', '=', $identity_card)
                ->whereRaw(dateAddSql('created_at', $setting_alert->from_time, 'day') ." <= '". now() ."'")
                ->whereRaw(dateAddSql('created_at', $setting_alert->to_time, 'day') ." >= '". now() ."'")
                ->first();

            if ($user_second){
                $this->errors[] = 'CMND '. $user_second->identity_card .' đã được thêm trước đó';
            }
        }

        if(empty($username)){
            $this->errors[] = 'Tên đăng nhập dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if(empty($password)){
            $this->errors[] = 'Mật khẩu dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if(empty($user_code)){
            $this->errors[] = 'Mã nhân viên dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if(empty($row[2])){
            $this->errors[] = 'Họ tên dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if(strlen($username) < 6 || strlen($username) > 32){
            $this->errors[] = 'Tên đăng nhập <b>'. $row[3] .'</b> phải trong khoảng 6 - 32 ký tự';
            $error = true;
        }

        if(strlen($password) < 8 || strlen($password) > 32){
            $this->errors[] = 'Mật khẩu <b>'. $row[4] .'</b> phải trong khoảng 8 - 32 ký tự';
            $error = true;
        }

        if(strlen($identity_card) < 9 || strlen($identity_card) > 14){
            $this->errors[] = 'Số CMND <b>'. $row[7] .'</b> phải trong khoảng 9 - 14 ký tự';
            $error = true;
        }

        if(isset($code)){
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã tồn tại';
            $error = true;
        }

        if(isset($user_name)){
            $this->errors[] = 'Tên đăng nhập <b>'. $row[3] .'</b> đã tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        QuizUserSecondary::create([
            'code' => $user_code,
            'name' => $row[2],
            'username' => 'secondary_'.$username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'dob' => date_convert($row[5]),
            'email' => $row[6],
            'identity_card' => $identity_card,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
