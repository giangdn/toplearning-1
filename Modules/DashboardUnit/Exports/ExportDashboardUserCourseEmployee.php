<?php


namespace Modules\DashboardUnit\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\Categories\TrainingForm;
use App\TypeCost;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Config;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Categories\TrainingType;
use Modules\DashboardUnit\Entities\DashboardUnitByCourse;
use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;

class ExportDashboardUserCourseEmployee implements WithMultipleSheets
{
    use Exportable;

    public function __construct($type, $unit_user, $child_arr, $area, $unit_type, $unit, $start_date, $end_date) {
        $this->type = $type;
        $this->unit_user = $unit_user;
        $this->child_arr = $child_arr;
        $this->unit = $unit;
        $this->unit_type = $unit_type;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->area = $area;
    }

    public function sheets(): array
    {
        $sheet = [];
        $sheet[] = new ExportDashboardUserCourseEmployeeSheet1($this->type, $this->unit_user, $this->child_arr, $this->area, $this->unit_type, $this->unit, $this->start_date, $this->end_date);

        $sheet[] = new ExportDashboardUserCourseEmployeeSheet2($this->type, $this->unit_user, $this->child_arr, $this->area, $this->unit_type, $this->unit, $this->start_date, $this->end_date);

        return $sheet;
    }
}
