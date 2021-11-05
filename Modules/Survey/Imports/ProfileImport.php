<?php
namespace Modules\Survey\Imports;

use Modules\Survey\Entities\SurveyObject;
use App\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProfileImport implements ToModel, WithStartRow
{
    public $errors;
    public $survey_id;

    public function __construct($survey_id)
    {
        $this->errors = [];
        $this->survey_id = $survey_id;
    }

    public function model(array $row)
    {
        $error = false;
        $user_code = (string)$row[1];

        $profile = Profile::where('code', '=', $user_code)->first();

        if($profile){
            $survey = SurveyObject::where('user_id', '=', $profile->user_id)
            ->where('survey_id', '=', $this->survey_id)->first();

            if ($survey) {
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

        SurveyObject::create([
            'user_id' =>(int) $profile->user_id,
            'survey_id' => $this->survey_id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
