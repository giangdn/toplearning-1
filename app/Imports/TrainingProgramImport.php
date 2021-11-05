<?php
namespace App\Imports;
use App\Models\Categories\TrainingProgram;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;
use App\Models\Categories\UnitType;

class TrainingProgramImport implements WithStartRow, ToModel
{
    use Importable;
    public $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function model(array $row)
    {
        $error = false;
        $checkTitleRank = '';
        if (!isset($row[1])) {
            $this->errors[] = 'Mã chương trình đào tạo Dòng '. $row[0] .': không được trống';
            $error = true;
        }

        if (!isset($row[2])) {
            $this->errors[] = 'Tên chương trình đào tạo Dòng '. $row[0] .': không được trống';
            $error = true;
        }

        if($error) {
            return null;
        }

        $model = TrainingProgram::firstOrNew(['code' => trim($row[1])]);
        $model->code = trim($row[1]);
        $model->name = $row[2];
        $model->status = 1;
        $model->save();
    }

    public function startRow(): int
    {
        return 2;
    }
}
