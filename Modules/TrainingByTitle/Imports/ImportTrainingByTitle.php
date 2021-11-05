<?php
namespace Modules\TrainingByTitle\Imports;

use App\Models\Categories\Titles;
use App\Models\Categories\Subject;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\TrainingByTitle\Entities\TrainingByTitle;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;

class ImportTrainingByTitle implements ToModel, WithStartRow
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
        $category_name = $row[3];
        $num_date_category = $row[4];
        $subject_code = trim($row[5]);
        $num_time = $row[7];

        if (empty($title_code)){
            $this->errors[] = 'Mã chức danh dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if (!isset($num_date_category)){
            $this->errors[] = 'Thời gian cần hoàn thành dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if (empty($subject_code)){
            $this->errors[] = 'Mã Khóa học dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if (!isset($num_time)){
            $this->errors[] = 'Thời lượng dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if (empty($category_name)){
            $this->errors[] = 'Tên danh mục <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        $title = Titles::where('code', '=', $title_code)->first();
        if (empty($title)) {
            $this->errors[] = 'Mã chức danh <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        $subject = Subject::where('code', '=', $subject_code)->first();
        if (empty($subject)) {
            $this->errors[] = 'Mã Khóa học <b>'. $row[2] .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        $query = TrainingByTitleCategory::firstOrNew(['title_id' => $title->id, 'name' => $category_name ]);
        $query->training_title_id = $title->id;
        $query->title_id = $title->id;
        $query->name = $category_name;
        $query->num_date_category = $num_date_category;
        $save = $query->save();

        if($save) {
            $model = TrainingByTitleDetail::firstOrNew(['title_id' => $title->id, 'training_title_category_id' => $query->id, 'subject_id' => $subject->id]);
            $model->training_title_category_id = $query->id;
            $model->training_title_id = $title->id;
            $model->title_id = $title->id;
            $model->subject_id = $subject->id;
            $model->subject_code = $subject->code;
            $model->subject_name = $subject->name;
            $model->num_date = $num_date_category;
            $model->num_time = $num_time;
            $model->save();
        }
    }
}
