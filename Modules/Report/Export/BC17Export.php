<?php
namespace Modules\Report\Export;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BC17Export implements WithMultipleSheets
{
    use Exportable;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->quiz_id = $param->quiz_id;
    }

    public function sheets(): array
    {
        $sheet = [];

        $sheet[] = new BC17ExportSheet1($this->quiz_id, $this->from_date, $this->to_date);
//        $sheet[] = new BC17ExportSheet2($this->quiz_id, $this->from_date, $this->to_date);

        return $sheet;
    }
}