<?php
namespace Modules\Capabilities\Imports;

use App\Models\Categories\Subject;
use Modules\Capabilities\Entities\CapabilitiesTitle;
use Modules\Capabilities\Entities\CapabilitiesTitleSubject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CapabilitiesTitleSubjectImport implements ToModel, WithStartRow
{
    public $errors;
    public $capabilities_title_id;

    public function __construct($capabilities_title_id)
    {
        $this->capabilities_title_id = $capabilities_title_id;
        $this->errors = [];
    }

    public function model(array $row)
    {
        $error = false;

        $subject_code = $row[1];
        $level = (int) $row[2];
        
        $subject = Subject::where('code', '=', $subject_code)->first();
        if ($subject)
        {
            $subject_id = (int) $subject->id;
            $capabilities_title_subject = CapabilitiesTitleSubject::where('subject_id', '=', $subject_id)->where('level', '=', $level)->first();

            if ($capabilities_title_subject) {
                $this->errors[] = 'Học phần có cấp độ <b>'. $row[2] .'</b> đã tồn tại';
                $error = true;
            }
        }

        if (empty($subject)) {
            $this->errors[] = 'Mã học phần <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if (!in_array($level, [1, 2, 3, 4])) {
            $this->errors[] = 'Cấp độ không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        CapabilitiesTitleSubject::create([
            'capabilities_title_id' => $this->capabilities_title_id,
            'subject_id' => $subject_id,
            'level' => $level,
        ]);
    }
    
    public function startRow(): int
    {
        return 2;
    }

}