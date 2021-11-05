<?php
namespace Modules\Report\Export;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
;

class BC22Export implements WithMultipleSheets
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

        $sheet[] = new BC22ExportSheet1($this->quiz_id, $this->from_date, $this->to_date);
        $sheet[] = new BC22ExportSheet2($this->quiz_id, $this->from_date, $this->to_date);


        return $sheet;
    }
}