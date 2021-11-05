<?php
namespace Modules\Report\Export;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Profile;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Modules\Report\Entities\BC37;
use Modules\Report\Entities\BC40;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BC40Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithCharts
{
    use Exportable, RegistersEventListeners;
    protected $index = 0;
    protected $count = 0;
    protected $from_date, $to_date, $unit_id, $area_id, $course_type, $created_by;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->unit_id = $param->unit_id;
        $this->area_id = $param->area_id;
        $this->course_type = $param->course_type;
        $this->created_by = $param->created_by;
    }

    public function query()
    {
        $unit = Unit::find($this->unit_id);
        $area = Area::find($this->area_id);

        $query = Area::query();
        $query->select([
            'unit.*',
            'area5.code as area_code',
            'area5.name as area_5',
            'area4.name as area_4',
            'area3.name as area_3',
            'area2.name as area_2',
        ]);
        $query->from('el_area as area5');
        $query->leftJoin('el_area as area4', 'area4.code', '=', 'area5.parent_code');
        $query->leftJoin('el_area as area3', 'area3.code', '=', 'area4.parent_code');
        $query->leftJoin('el_area as area2', 'area2.code', '=', 'area3.parent_code');
        $query->leftJoin('el_unit as unit', 'area2.unit_id', '=', 'unit.id');
        if ($unit->level == 2){
            $query->where('unit.parent_code', '=', $unit->code);
        }else{
            $query->where('unit.id', '=', $unit->id);
        }
        if ($area){
            $query->where('area'.$area->level.'.id', '=', $area->id);
        }
        $query->orderBy('area5.id', 'ASC');

        return $query;
    }

    public function map($row): array
    {
        $from_date = date_convert($this->from_date);
        $to_date = date_convert($this->to_date);
        $unit = Unit::find($this->unit_id);
        $course_type = $this->course_type;
        $onl_regsiter = 0;
        $onl_completed = 0;
        $off_regsiter = 0;
        $off_completed = 0;

        $this->index++;
        if ($unit->level == 2){
            $unit_2 = $unit->name;
            $unit_3 = $row->name;
        }else{
            $parent = Unit::where('code', '=', $row->parent_code)->first();
            $unit_2 = $parent->name;
            $unit_3 = $row->name;
        }

        if ($course_type){
            if ($course_type == 1){
                $onl_regsiter = BC40::countRegister($row->code, $row->area_code, $from_date, $to_date, $course_type);
                $onl_completed = BC40::countCompleted($row->code, $row->area_code, $from_date, $to_date, $course_type);
            }else{
                $off_regsiter = BC40::countRegister($row->code, $row->area_code, $from_date, $to_date, $course_type);
                $off_completed = BC40::countCompleted($row->code, $row->area_code, $from_date, $to_date, $course_type);
            }
        }else{
            $onl_regsiter = BC40::countRegister($row->code, $row->area_code, $from_date, $to_date, 1);
            $off_regsiter = BC40::countRegister($row->code, $row->area_code, $from_date, $to_date, 2);

            $onl_completed = BC40::countCompleted($row->code, $row->area_code, $from_date, $to_date, 1);
            $off_completed = BC40::countCompleted($row->code, $row->area_code, $from_date, $to_date, 2);
        }

        return [
            $this->index,
            $unit_2,
            $unit_3,
            $row->area_2,
            $row->area_3,
            $row->area_4,
            $row->area_5,
            $onl_regsiter,
            $onl_completed,
            $off_regsiter,
            $off_completed,
        ];
    }

    public function headings(): array
    {
        $area = Area::find($this->area_id);
        $unit = Unit::find($this->unit_id);
        if ($unit->level == 2){
            $company = $unit->name;
            $department = '';
        }else{
            $parent = Unit::where('code', '=', $unit->parent_code)->first();

            $company = $parent->name;
            $department = $unit->name;
        }

        return [
            ['BÁO CÁO TÌNH HÌNH ĐÀO TẠO THEO KÊNH PHÂN PHỐI'],
            ['Thời gian báo cáo', 'từ '. $this->from_date .' - '. $this->to_date],
            ['Công ty', $company],
            ['Kênh PP', $department],
            ['Miền', ($this->area_id ? $area->name : '')],
            ['Khóa học thống kê', ($this->course_type == 1 ? 'Offline' : ($this->course_type == 2 ? 'Tập trung (In house)' : 'Cả hai'))],
            ['Người lập', Profile::fullname($this->created_by) .' ('. Profile::usercode($this->created_by) .')'],
            [''],
            [
                'STT',
                'Công ty',
                'Kênh phân phối (Phòng ban)',
                'Miền',
                'Khu vực',
                'Vùng',
                'Văn phòng',
                'Online',
                '',
                'In house',
                ''
            ],
            [
                '','','','','','','',
                'Đăng ký',
                'Hoàn thành',
                'Đăng ký',
                'Hoàn thành'
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $from_date = date_convert($this->from_date);
                $to_date = date_convert($this->to_date);
                $course_type = $this->course_type;

                $header = [
                    'font' => [
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $content = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name' => 'Arial',
                    ],
                ];

                $event->sheet->getDelegate()->mergeCells('A1:K1');
                $event->sheet->getDelegate()->mergeCells('A9:A10');
                $event->sheet->getDelegate()->mergeCells('B9:B10');
                $event->sheet->getDelegate()->mergeCells('C9:C10');
                $event->sheet->getDelegate()->mergeCells('D9:D10');
                $event->sheet->getDelegate()->mergeCells('E9:E10');
                $event->sheet->getDelegate()->mergeCells('F9:F10');
                $event->sheet->getDelegate()->mergeCells('G9:G10');
                $event->sheet->getDelegate()->mergeCells('H9:I9');
                $event->sheet->getDelegate()->mergeCells('J9:K9');

                $event->sheet->getDelegate()->getStyle('A1')->applyFromArray($header);

                $event->sheet->getDelegate()->getStyle('A9:K'.(10 + $this->index))->applyFromArray($content);

                $event->sheet->getDelegate()->getStyle('A9:K10')->applyFromArray($header);
                $event->sheet->getDelegate()->getStyle('A9:A'.(10 + $this->index))->applyFromArray($header);
                $event->sheet->getDelegate()->getStyle('H9:K'.(10 + $this->index))->applyFromArray($header);

                $event->sheet->getDelegate()->setCellValue('L1', 'Địa điểm');
                $event->sheet->getDelegate()->setCellValue('M1', 'Tổng nhân viên');
                $event->sheet->getDelegate()->setCellValue('N1', 'HV được ghi danh');
                $event->sheet->getDelegate()->setCellValue('O1', 'HV hoàn thành');

                $rows = $this->queryChart();
                foreach ($rows as $key => $row){
                    $area_query = Area::query();
                    $area_query->select([
                        'area5.code as area_code',
                    ]);
                    $area_query->from('el_area as area5');
                    $area_query->leftJoin('el_area as area4', 'area4.code', '=', 'area5.parent_code');
                    $area_query->leftJoin('el_area as area3', 'area3.code', '=', 'area4.parent_code');
                    $area_query->leftJoin('el_area as area2', 'area2.code', '=', 'area3.parent_code');
                    $area_query->where('area'.$row->area_level.'.id', '=', $row->area_id);
                    $area_query = $area_query->get();

                    $regsiter = 0;
                    $completed = 0;
                    $total_profile = 0;
                    foreach ($area_query as $item) {
                        $total_profile += Profile::where('unit_code', '=', $row->code)->where('area_code', '=', $item->area_code)->where('status','=',1)->count();
                        if ($course_type) {
                            if ($course_type == 1) {
                                $regsiter += BC40::countRegister($row->code, $item->area_code, $from_date, $to_date, $course_type);
                                $completed += BC40::countCompleted($row->code, $item->area_code, $from_date, $to_date, $course_type);
                            } else {
                                $regsiter += BC40::countRegister($row->code, $item->area_code, $from_date, $to_date, $course_type);
                                $completed += BC40::countCompleted($row->code, $item->area_code, $from_date, $to_date, $course_type);
                            }
                        } else {
                            $onl_regsiter = BC40::countRegister($row->code, $item->area_code, $from_date, $to_date, 1);
                            $off_regsiter = BC40::countRegister($row->code, $item->area_code, $from_date, $to_date, 2);

                            $onl_completed = BC40::countCompleted($row->code, $item->area_code, $from_date, $to_date, 1);
                            $off_completed = BC40::countCompleted($row->code, $item->area_code, $from_date, $to_date, 2);

                            $regsiter += ($onl_regsiter + $off_regsiter);
                            $completed += ($onl_completed + $off_completed);
                        }
                    }
                    $event->sheet->getDelegate()->setCellValue('L'.(2 + $key), $row->area_name);
                    $event->sheet->getDelegate()->setCellValue('M'.(2 + $key), $total_profile);
                    $event->sheet->getDelegate()->setCellValue('N'.(2 + $key), $regsiter);
                    $event->sheet->getDelegate()->setCellValue('O'.(2 + $key), $completed);
                }

                $event->sheet->getDelegate()->getStyle('L1:O'.(1 + $rows->count()))->applyFromArray($content);
                $event->sheet->getDelegate()->getStyle('M2:O'.(1 + $rows->count()))->applyFromArray($header);
            },
        ];
    }

    public function startRow(): int
    {
        return 10;
    }

    public function charts() {
        $type = ($this->course_type == 1 ? 'Offline' : ($this->course_type == 2 ? 'Tập trung (In house)' : 'Cả hai'));
        $this->count = $this->query()->count();

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$M$1', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$N$1', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$O$1', null, 1),
        ];

        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$L$2:$L$'.(1 + $this->count), null, ($this->count)),
        ];

        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$M$2:$M$'.(1 + $this->count), null, ($this->count)),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$N$2:$N$'.(1 + $this->count), null, ($this->count)),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$O$2:$O$'.(1 + $this->count), null, ($this->count)),
        ];


        //	Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_STANDARD, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );

        $series->setPlotDirection(DataSeries::DIRECTION_COL);
        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);

        $title = new Title('Biểu đồ tình hình học tập - '.$type);

        $chart = new Chart(
            'chart1',
            $title,
            $legend,
            $plotArea
        );

        //	Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('Q3');
        $chart->setBottomRightPosition('AE20');

        return $chart;
    }

    public function queryChart(){
        $area = Area::find($this->area_id);
        $unit = Unit::find($this->unit_id);

        $query = Area::query();
        $query->select([
            'unit.*',
            'area.id as area_id',
            'area.level as area_level',
            'area.name as area_name',
        ]);
        $query->from('el_area as area');
        if ($area){
            if ($area->level == 2){
                $query->leftJoin('el_area as parent', 'parent.code', '=', 'area.parent_code');
                $query->leftJoin('el_unit as unit', 'parent.unit_id', '=', 'unit.id');
                $query->where('parent.id', '=', $area->id);
            }
            if ($area->level == 3){
                $query->leftJoin('el_area as parent3', 'parent3.code', '=', 'area.parent_code');
                $query->leftJoin('el_area as parent2', 'parent2.code', '=', 'parent3.parent_code');
                $query->leftJoin('el_unit as unit', 'parent2.unit_id', '=', 'unit.id');
                $query->where('parent3.id', '=', $area->id);
            }
            if ($area->level == 4){
                $query->leftJoin('el_area as parent4', 'parent4.code', '=', 'area.parent_code');
                $query->leftJoin('el_area as parent3', 'parent3.code', '=', 'parent4.parent_code');
                $query->leftJoin('el_area as parent2', 'parent2.code', '=', 'parent3.parent_code');
                $query->leftJoin('el_unit as unit', 'parent2.unit_id', '=', 'unit.id');
                $query->where('parent4.id', '=', $area->id);
            }
            if ($area->level == 5){
                $query->leftJoin('el_area as parent4', 'parent4.code', '=', 'area.parent_code');
                $query->leftJoin('el_area as parent3', 'parent3.code', '=', 'parent4.parent_code');
                $query->leftJoin('el_area as parent2', 'parent2.code', '=', 'parent3.parent_code');
                $query->leftJoin('el_unit as unit', 'parent2.unit_id', '=', 'unit.id');
                $query->where('area.id', '=', $area->id);
            }
        }else{
            $query->leftJoin('el_unit as unit', 'area.unit_id', '=', 'unit.id');
        }

        if ($unit->level == 2){
            $query->where('unit.parent_code', '=', $unit->code);
        }else{
            $query->where('unit.id', '=', $unit->id);
        }
        return $query->get();
    }
}
