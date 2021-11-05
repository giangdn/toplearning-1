<?php
namespace Modules\Report\Export;

use App\Models\Categories\Unit;
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
use Modules\DailyTraining\Entities\DailyTrainingUserViewVideo;
use Modules\Report\Entities\BC39;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BC39Export implements WithHeadings, ShouldAutoSize,  WithEvents, WithStartRow, WithCharts
{
    use Exportable, RegistersEventListeners;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param){}

    public function headings(): array
    {
        $day = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
        $data[] = 'Ngày';
        $data1[] = 'Số lượng';

        for($i = 1; $i <= $day; $i++){
            $data[] = $i;
            $data1[] = BC39::countViewVideoInMonth($i);
        }

        return [
            ['BÁO CÁO THỐNG KÊ SỐ LƯỢNG XEM VIDEO TỪNG NGÀY TRONG THÁNG'],
            [],
            $data,
            $data1
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
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
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $num_char = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')) + 1;
                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                if ($num_char > 26){
                    $num = floor($num_char/26);
                    $num_1 = $num_char - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($num_char - 1)];
                }

                $event->sheet->getDelegate()->mergeCells('A1:'.$char.'1');
                $event->sheet->getDelegate()->getStyle('A1:'.$char.'1')->applyFromArray($header);

                $event->sheet->getDelegate()->getStyle('A3:'.$char.'4')->applyFromArray($content);
            },
        ];
    }

    public function startRow(): int
    {
        return 4;
    }

    public function charts() {
        $day = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
        $num_char = $day + 1;
        $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        if ($num_char > 26){
            $num = floor($num_char/26);
            $num_1 = $num_char - ($num * 26);

            $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
        }else{
            $char = $arr_char[($num_char - 1)];
        }

        $dataSeriesLabels = [];
        $dataSeriesValues = [];
        $xAxisTickValues = [];

        $dataSeriesLabels[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$4', null, 1);

        $dataSeriesValues[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$4:$'.$char.'$4', null, $day);

        $xAxisTickValues[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$3:$'.$char.'$3', null, $day);
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

        $title = new Title('SỐ LƯỢNG XEM VIDEO TỪNG NGÀY TRONG THÁNG');

        $chart = new Chart(
            'chart1',
            $title,
            $legend,
            $plotArea
        );

        //	Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('AH3');
        $chart->setBottomRightPosition('AW20');

        return $chart;
    }
}
