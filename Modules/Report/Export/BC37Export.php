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
use Modules\Report\Entities\BC37;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BC37Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithCharts
{
    use Exportable, RegistersEventListeners;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param)
    {
    }

    public function query()
    {
        $query = Unit::where('status', '=', 1);
        $query->orderBy('id', 'ASC');

        return $query;
    }

    public function map($report): array
    {
        $this->index++;
        return [
            $this->index,
            $report->name,
            BC37::countVideoInMonth($report->id)
        ];
    }

    public function headings(): array
    {
        return [
            ['BÁO CÁO THỐNG KÊ SỐ LƯỢNG VIDEO CỦA ĐƠN VỊ XUẤT BẢN TRONG THÁNG'],
            [],
            [
                'STT',
                'Đơn vị',
                'Số lượng video trong tháng',
            ]
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

                $event->sheet->getDelegate()->mergeCells('A1:C1');
                $event->sheet->getDelegate()->getStyle('A1:C1')->applyFromArray($header);

                $event->sheet->getDelegate()->getStyle('A3:C3')->applyFromArray($content);
                $event->sheet->getDelegate()->getStyle('A3:C'.(3 + $this->index))->applyFromArray($content);
            },
        ];
    }

    public function startRow(): int
    {
        return 4;
    }

    public function charts() {
        $this->count = $this->query()->count();

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$3', null, 1),
        ];

        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$4:$B$'.(3 + $this->count), null, ($this->count)),
        ];

        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$4:$C$'.(3 + $this->count), null, ($this->count)),
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

        $title = new Title('SỐ LƯỢNG VIDEO CỦA ĐƠN VỊ XUẤT BẢN TRONG THÁNG');

        $chart = new Chart(
            'chart1',
            $title,
            $legend,
            $plotArea
        );

        //	Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('G3');
        $chart->setBottomRightPosition('P20');

        return $chart;
    }
}
