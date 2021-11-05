<?php
namespace Modules\Report\Export;

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
use Modules\Report\Entities\BC33;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class BC33Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithCharts
{
    use Exportable, RegistersEventListeners;
    protected $index = 0;
    private $from_date;
    private $to_date;
    private $type;
    private $training_form;
    
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->type = $param->type;
        $this->training_form = $param->training_form;
    }
    
    public function query()
    {
        $query = \DB::query();
        $query->select([
            'id',
            'name'
        ]);
        
        $query->from('el_training_form');
        if ($this->training_form) {
            $query->whereIn('id', $this->training_form);
        }
        
        $query->orderBy('name', 'ASC');
        
        return $query;
    }
    
    public function map($report): array
    {
        $this->index++;
        return [
            $this->index,
            $report->name,
            intval(BC33::countCourseActive([$report->id], $this->type, $this->from_date, $this->to_date)),
            BC33::countCourseUpcoming([$report->id], $this->type, $this->from_date, $this->to_date),
            BC33::countCourseFinished([$report->id], $this->type, $this->from_date, $this->to_date),
        ];
    }
    
    public function headings(): array
    {
        return [
            ['BÁO CÁO THỐNG KÊ SỐ LƯỢNG KHÓA HỌC'],
            ['Từ '.get_date($this->from_date).' - '.get_date($this->to_date)],
            [],
            [
                'STT',
                'Hình thức',
                'Đang diễn ra',
                'Sắp diễn ra',
                'Đã kết thúc',
            ]
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $header = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    
                    ],
                    'font' => [
                        'size'      =>  12,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()
                    ->getStyle("A".($this->startRow()-1).":E".($this->startRow()-1))
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');
                $event->sheet->getDelegate()->mergeCells('A1:E1')->getStyle('A1')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A2:E2')->getStyle('A2')->applyFromArray($title);
            },
        
        ];
    }
    
    public function startRow(): int
    {
        return 5;
    }
    
    public function charts() {
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$4', null, 1), //	2010
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$4', null, 1), //	2011
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$E$4', null, 1), //	2012
        ];
    
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$5:$B$7', null, 3), //	Q1 to Q4
        ];
    
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$5:$C$7', null, 3),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$5:$D$7', null, 3),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$E$5:$E$7', null, 3),
        ];
    
        //	Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_STACKED, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );
    
        $series->setPlotDirection(DataSeries::DIRECTION_BAR);
        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);
    
        $title = new Title('Test Stacked Line Chart');
        $yAxisLabel = new Title('Khóa học (khóa)');
        
        $chart = new Chart(
            'chart1',
            $title,
            $legend,
            $plotArea,
            true,
            0,
            null,
            $yAxisLabel
        );
    
        //	Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('G3');
        $chart->setBottomRightPosition('P20');
        
        return $chart;
    }
}