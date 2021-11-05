<?php
namespace Modules\TrainingRoadmap\Imports;

use App\Models\Categories\Titles;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use App\Models\Categories\Subject;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TrainingRoadmapImport implements ToModel, WithStartRow
{
    public $errors;
    public $title_id;

    public function __construct()
    {
        $this->errors = [];
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $error = false;
        $title_code = trim($row[1]);
        $subject_code = trim($row[3]);
        $training_form = explode(',', $row[5]);
        $completion_time = $row[6];
        $order = $row[7];
        $content = $row[8];

        if (empty($title_code)){
            $this->errors[] = 'Mã chức danh dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if (empty($subject_code)){
            $this->errors[] = 'Mã tài liệu dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if (empty($training_form)){
            $this->errors[] = 'Hình thức <b>'. $row[1] .'</b> không được trống';
            $error = true;
        }

        if ( !in_array(1, $training_form) && !in_array(2, $training_form) ){
            $this->errors[] = 'Hình thức <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        } 

        $title = Titles::where('code', '=', $title_code)->first();
        if (empty($title)) {
            $this->errors[] = 'Mã chức danh <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        $subject = Subject::where('code', '=', $subject_code)->first();
        if (empty($subject)) {
            $this->errors[] = 'Mã tài liệu <b>'. $row[2] .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        $model = TrainingRoadmap::firstOrNew(['title_id' => $title->id, 'subject_id' => $subject->id]);
        $model->training_program_id = $subject->training_program_id;
        $model->title_id = $title->id;
        $model->subject_id = $subject->id;
        $model->training_form = json_encode($training_form);
        $model->completion_time = $completion_time ? $completion_time : null;
        $model->order = $order ? $order : 1;
        $model->content = $content ? $content : null;
        $model->save();
    }
}
