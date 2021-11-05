<?php
namespace App\Imports;

use App\Models\Categories\Titles;
use App\Models\Categories\TitleRank;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;
use App\Models\Categories\UnitType;

class TitlesImport implements WithStartRow, ToModel
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
        $checkUnitType = '';
        if (isset($row[3])) {
            $checkTitleRank = TitleRank::where('code',trim($row[3]))->first();
            // dd($checkTitleRank);
            if(empty($checkTitleRank)) {
                $this->errors[] = 'Dòng '. $row[0] .': Mã Cấp bậc không đúng';
                $error = true;
            }
        }

        $checkUnitType = '';
        if (isset($row[4])) {
            $checkUnitType = UnitType::where('name','like','%'.trim($row[4]).'%')->first();
            if(!$checkUnitType) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }

        if (empty($row[3])){
            $this->errors[] = 'Dòng '. $row[0] .': Mã Cấp bậc không được trống';
            $error = true;
        }

        if($error) {
            return null;
        }

        $model = Titles::firstOrNew(['code' => trim($row[1])]);
        $model->code = trim($row[1]);
        $model->name = $row[2];
        $model->group = $checkTitleRank ? $checkTitleRank->id : '';
        $model->unit_type = !empty($checkUnitType) ? $checkUnitType->id : '';
        $model->status = 1;
        $model->save();
    }

    public function startRow(): int
    {
        return 2;
    }
}
