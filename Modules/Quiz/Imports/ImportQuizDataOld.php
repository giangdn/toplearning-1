<?php
namespace Modules\Quiz\Imports;

use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Entities\QuizDataOld;
use App\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportQuizDataOld implements ToModel, WithStartRow
{
    public $errors;

    public function __construct() {
    }

    public function model(array $row)
    {
        $error = false;

        if (empty($row[1])) {
            $this->errors[] = 'Mã nhân viên dòng: '. $row[0] .' không được trống';
            $error = true;
        }
        if (empty($row[8])) {
            $this->errors[] = 'Mã kỳ thi dòng: '. $row[0] .' không được trống';
            $error = true;
        }

        if($error) {
            return null;
        }

        $model = QuizDataOld::firstOrNew(['user_code' => $row[1], 'quiz_code' => $row[8]]);
        $model->user_code = isset($row[1]) ? $row[1] : '';
        $model->user_name = isset($row[2]) ? $row[2] : '';
        $model->title = isset($row[3]) ? $row[3] : '';
        $model->area = isset($row[4]) ? $row[4] : '';
        $model->unit = isset($row[5]) ? $row[5] : '';
        $model->department = isset($row[6]) ? $row[6] : '';
        $model->phone = isset($row[7]) ? $row[7] : '';
        $model->email = isset($row[8]) ? $row[8] : '';
        $model->quiz_code = isset($row[9]) ? $row[9] : null;
        $model->quiz_name = isset($row[10]) ? $row[10] : null;
        $model->start_date = isset($row[11]) ? date('Y-m-d', strtotime(str_replace('/', '-', $row[11]))) : null;
        $model->end_date = isset($row[12]) ? date('Y-m-d', strtotime(str_replace('/', '-', $row[12]))) : null;
        $model->score_essay = isset($row[13]) ? $row[13] : '';
        $model->score_multiple_choice = isset($row[14]) ? $row[14] : '';
        $model->result = isset($row[15]) ? $row[15] : '';
        $model->save();
    }
    
    public function startRow(): int
    {
        return 2;
    }

}