<?php
namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class IHRPTemplate2Export implements WithMultipleSheets
{
    use Exportable;
    protected $index = 0;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function sheets(): array
    {
        $sheet = [];

        $sheet[] = new IHRPTemplate2Sheet1($this->start_date, $this->end_date);
        $sheet[] = new IHRPTemplate2Sheet2();
        $sheet[] = new IHRPTemplate2Sheet3();

        return $sheet;
    }
}