<?php
namespace Modules\ConvertTitles\Imports;

use App\Models\Categories\TrainingProgram;
use Modules\ConvertTitles\Entities\ConvertTitlesRoadmap;
use App\Models\Categories\Titles;
use App\Models\Categories\Subject;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ConvertTitlesRoadmapImport implements ToModel, WithStartRow
{
    public $title_id;
    public $errors;

    public function __construct($title_id)
    {
        $this->title_id = $title_id;
        $this->errors = [];
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $error = false;

        $training_program_code = $row[1];
        $subject_code = $row[2];
        $training_form = (int) $row[3];
        $completion_time = $row[4];
        $order = $row[5] ;
        $content = $row[6];

        $training_program = TrainingProgram::where('code', '=', $training_program_code)->first();
        if (empty($training_program)) {
            $this->errors[] = 'Mã chương trình đào tạo <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        $subject = '';
        if ($training_program){
            $subject = Subject::where('code', '=', $subject_code)
                ->where('training_program_id', '=', $training_program->id)
                ->first();
        }
        if (empty($subject)) {
            $this->errors[] = 'Mã học phần <b>'. $row[2] .'</b> không tồn tại';
            $error = true;
        }

        $training_roamap = '';
        if ($subject){
            $training_roamap = ConvertTitlesRoadmap::where('training_program_id', '=', $training_program->id)
                ->where('subject_id', '=', $subject->id)
                ->where('title_id','=',$this->title_id)
                ->first();
        }
        if ($training_roamap) {
            $this->errors[] = 'Mã học phần <b>'. $row[2] .'</b> đã tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        ConvertTitlesRoadmap::create([
            'title_id' => $this->title_id,
            'subject_id' =>$subject->id,
            'training_program_id' => $training_program->id,
            'completion_time' => (int) $completion_time,
            'training_form' => $training_form,
            'order' => $order,
            'content' => $content,
        ]);
    }

}