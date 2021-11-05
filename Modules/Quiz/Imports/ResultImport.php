<?php
namespace Modules\Quiz\Imports;

use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Entities\QuizResult;
use App\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ResultImport implements ToModel, WithStartRow
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
        $user_code = $row[1];
        $type = (int) $row[2];
        $grade = $row[3];
        $reexamine = $row[4];
        
        if ($type == 1) {
            $profile = Profile::where('code', '=', $user_code)->first();
            $register = QuizRegister::where('user_id', '=', $profile->user_id)->first();
        }
        else {
            $profile = QuizUserSecondary::where('code', '=', $user_code)->first();
            $register = QuizRegister::where('user_secondary_id', '=', $profile->id)->first();
        }

        if (empty($profile)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if (empty($register)) {
            $this->errors[] = 'Nhân viên <b>'. $row[1] .'</b> chưa ghi danh kỳ thi';
            $error = true;
        }

        if($error) {
            return null;
        }

        if ($type == 1) {
            $result = QuizResult::where('quiz_id', '=', $this->quiz_id)
                ->where('user_id', '=', $profile->user_id)
                ->where('type', '=', $type);
        }
        else {
            $result = QuizResult::where('quiz_id', '=', $this->quiz_id)
                ->where('user_secondary_id', '=', $profile->id)
                ->where('type', '=', $type);
        }
        
        if(!$result->exists()){
            return;
        }

        $result_id = $result->first()->id;
        $model = QuizResult::find($result_id);
        $model->grade = isset($grade) ? $grade : $model->grade;
        $model->reexamine = isset($reexamine) ? $reexamine : $model->reexamine;
        $model->save();
    }
    
    public function startRow(): int
    {
        return 2;
    }

}