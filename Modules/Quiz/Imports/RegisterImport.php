<?php
namespace Modules\Quiz\Imports;

use App\Automail;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use App\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\QuizSettingAlert;
use Modules\Quiz\Entities\QuizUserSecondary;

class RegisterImport implements ToModel, WithStartRow
{
    public $errors;
    public $quiz_id;

    public function __construct($quiz_id)
    {
        $this->errors = [];
        $this->quiz_id = $quiz_id;
    }

    public function model(array $row)
    {
        $error = false;
        $user_code = (string)$row[1];
        $full_name = (string)$row[2];
        $part_name = trim($row[3]);
        $type = $row[4] == 1 ? 1 : 2;

        $username = (string)$row[5];
        $password = (string)$row[6];
        $dob = (string)$row[7];
        $email = (string)$row[8];
        $identity_card = (string)$row[9];

        $quiz = Quiz::with('type')->find($this->quiz_id);
        $part = QuizPart::where('quiz_id', '=', $this->quiz_id)->where('name', '=', $part_name)->first();

        if ($type == 1){
            $profile = Profile::where('code', '=', $user_code)->first();

            if (empty($profile)) {
                $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại';
                $error = true;
            }
        }else{
            $profile = QuizUserSecondary::where('code', '=', $user_code)->first();
            if (empty($profile)) {
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

                if(empty($full_name)){
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
            }
        }

        if(isset($profile)){
            $user_id = ($type == 1 ? $profile->user_id : $profile->id);

            $register = QuizRegister::where('user_id', '=', $user_id)
                ->where('quiz_id', '=', $this->quiz_id)
                ->where('type', '=', $type)
                ->first();

            if ($register) {
                $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã đăng kí kỳ thi';
                $error = true;
            }
        }
        if (empty($part)){
            $this->errors[] = $row[3] .'</b> không thuộc kỳ thi này';
            $error = true;
        }

        if($error) {
            return null;
        }

        if (empty($profile) && $type == 2){
            $profile = QuizUserSecondary::create([
                'code' => $user_code,
                'name' => $full_name,
                'username' => 'secondary_'.$username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'dob' => $dob ? date_convert($dob) : null,
                'email' => $email,
                'identity_card' => $identity_card,
            ]);
        }

        QuizRegister::create([
            'user_id' =>(int) ($type == 1 ? $profile->user_id : $profile->id),
            'quiz_id' => $this->quiz_id,
            'part_id' => $part->id,
            'type' => $type,
        ]);

        if ($quiz->status == 1){
            $user_id = ($type == 1 ? $profile->user_id : $profile->id);
            $signature = getMailSignature($user_id, $type);
            $params = [
                'signature' => $signature,
                'gender' => $type == 1 ? ($profile->gender=='1'?'Anh':'Chị') : 'Anh/Chị',
                'full_name' => $type == 1 ? $profile->full_name : $profile->name,
                'quiz_name' => $quiz->name,
                'quiz_type' => $quiz->type? $quiz->type->name:'',
                'quiz_part_name' => $part->name,
                'start_quiz_part' => get_datetime($part->start_date),
                'end_quiz_part' => get_datetime($part->end_date),
                'quiz_time' => $quiz->limit_time,
                'pass_score' => $quiz->pass_score,
                'url' => route('module.quiz.doquiz.index', ['quiz_id' => $this->quiz_id,'part_id'=>$part->id])
            ];
            $this->saveEmailQuizRegister($params,[$user_id],$part->id,$type);
        }

    }

    public function startRow(): int
    {
        return 2;
    }

    public function saveEmailQuizRegister(array $params,array $user_id,int $part_id, int $user_type)
    {
        $automail = new Automail();
        $automail->template_code = 'quiz_registerd';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->user_type = $user_type;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $part_id;
        $automail->object_type = 'approve_quiz';
        $automail->addToAutomail();
    }
}
