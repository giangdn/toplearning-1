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
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Quiz\Entities\QuizType;
use Modules\Report\Entities\BC36;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class BC36Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithStrictNullComparison, WithCharts
{
    use Exportable, RegistersEventListeners;
    private $index;
    private $from_date;
    private $to_date;
    private $quiz_type;
    
    public function __construct($param)
    {
        $this->index = 0;
        $this->from_date = date_convert($param->from_date, '00:00:00');
        $this->to_date   = date_convert($param->to_date, '23:59:59');
        $this->quiz_type = $param->quiz_type;
    }
    
    public function query()
    {
        $query = QuizType::query();
        $query->select([
            'id',
            'name'
        ]);
    
        if ($this->quiz_type) {
            $query->whereIn('id', $this->quiz_type);
        }
        
        return $query;
    }
    
    public function map($report): array
    {
        $this->index ++;
        $total_completed = BC36::totalCompleted($this->from_date, $this->to_date, $report->id);
        $total_failed = BC36::totalFailed($this->from_date, $this->to_date, $report->id);
        
        return [
            $this->index,
            $report->name,
            BC36::totalQuiz1($this->from_date, $this->to_date, $report->id),
            BC36::totalQuiz2($this->from_date, $this->to_date, $report->id),
            BC36::totalQuiz3($this->from_date, $this->to_date, $report->id),
            BC36::totalRegister1($this->from_date, $this->to_date, $report->id),
            BC36::totalRegister2($this->from_date, $this->to_date, $report->id),
            BC36::totalRegister3($this->from_date, $this->to_date, $report->id),
            ($total_failed > 0 ? round($total_completed / $total_failed, 2) : 100)
        ];
    }
    
    public function headings(): array
    {
        return [
            ['BÁO CÁO TÌNH HÌNH TỔ CHỨC KỲ THI'],
            ['Từ '.get_date($this->from_date).' - '.get_date($this->to_date)],
            [],
            [
                'STT',
                'Thể loại',
                'Sắp diễn ra',
                '',
                'Đang diễn ra',
                '',
                'Đã kết thúc',
                '',
                'Tỉ lệ'
            ],
            [
                '',
                '',
                'Kỳ thi',
                'Thí sinh',
                'Kỳ thi',
                'Thí sinh',
                'Kỳ thi',
                'Thí sinh',
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
                    ->getStyle("A".($this->startRow()-1).":I".($this->startRow()-1))
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');
    
                $event->sheet->getDelegate()
                    ->getStyle("A5:I5")
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');
                
                $event->sheet->getDelegate()->mergeCells('A4:A5');
                $event->sheet->getDelegate()->mergeCells('B4:B5');
                $event->sheet->getDelegate()->mergeCells('I4:I5');
                $event->sheet->getDelegate()->mergeCells('C4:D4');
                $event->sheet->getDelegate()->mergeCells('E4:F4');
                $event->sheet->getDelegate()->mergeCells('G4:H4');
                $event->sheet->getDelegate()->mergeCells('A1:I1')->getStyle('A1')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A2:I2')->getStyle('A2')->applyFromArray($title);
            },
        ];
    }
    
    public function startRow(): int
    {
        return 5;
    }
    
    public function charts() {
        $query = $this->query();
        $rows = $query->get();
        
        $index = 6;
        $dataSeriesLabels = [];
        foreach ($rows as $row) {
            $dataSeriesLabels[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$'. $index . ':$B$' . $index, null, 1);
            $index++;
        }
        
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$4', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$E$4', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$G$4', null, 1),
        ];
    
        $index = 6;
        $dataSeriesValues = [];
        foreach ($rows as $row) {
            $dataSeriesValues[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$' . $index .':$E$' . $index, null, 3);
            $index++;
        }
        
        //	Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            null, // plotGrouping
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
        $chart->setTopLeftPosition('K3');
        $chart->setBottomRightPosition('Q12');
        
        return $chart;
    }
}