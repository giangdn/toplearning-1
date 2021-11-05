<?php
namespace Modules\Report\Export;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BC27Export implements WithMultipleSheets
{
    use Exportable;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->quiz_id = $param->quiz_id;
        $this->user_id = $param->user_id;
    }

    public function sheets(): array
    {
        $sheet = [];

        $sheet[] = new BC27ExportSheet1($this->quiz_id, $this->from_date, $this->to_date, $this->user_id);
        $sheet[] = new BC27ExportSheet2($this->quiz_id, $this->from_date, $this->to_date, $this->user_id);

        return $sheet;
    }
}