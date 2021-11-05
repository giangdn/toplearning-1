<?php
namespace Modules\Report\Export;

use App\Config;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizResult;
use Modules\Report\Entities\BC21;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Axis;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\GridLines;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Properties;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpParser\Node\Stmt\Label;

class BC21Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithCharts, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $index_chart = 0;
    protected $count = [];

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->quiz_id = $param->quiz_id;
    }

    public function query()
    {
        $query = BC21::sql($this->quiz_id, $this->from_date, $this->to_date)->orderBy('c.id', 'ASC');
        return $query;
    }

    public function map($report): array
    {
        $this->index++;

        $list = $this->countListByTitle($report->title_id);
        $absent = 0;
        foreach ($list as $item){
            $quiz_result = QuizResult::where('user_id', '=', $item->user_id)->first();
            if (is_null($quiz_result)){
                $absent++;
            }
        }
        return [
            $this->index,
            $report->title_name,
            $list->count(),
            ($list->count() - $absent),
            $absent,
        ];
    }

    public function headings(): array
    {
        $quiz = Quiz::find($this->quiz_id);
        return [
            [],
            [],
            [],
            [],
            [],
            [],
            ['THỐNG KÊ THÍ SINH TRONG KỲ THI THEO CHỨC DANH'],
            [$quiz ? $quiz->name : ''],
            ['Từ '. $this->from_date. ' đến '. $this->to_date],
            ['STT', 'Chức danh', 'Số lượng thí sinh', '', '', 'Ghi chú'],
            ['', '', 'Danh sách', 'Thực tế', 'Vắng mặt', ''],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size' =>  12,
                        'bold' =>  true,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->mergeCells('A7:F7')->getStyle('A7:F7')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A8:F8')->getStyle('A8:F8')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A9:F9')->getStyle('A9:F9')->applyFromArray($title);
                $event->sheet->getDelegate()->getStyle('A10:F11')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A10:F'.(11 + $this->index + 1).'')->applyFromArray([
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
                ]);

                $event->sheet->getDelegate()->mergeCells('A10:A11');
                $event->sheet->getDelegate()->mergeCells('B10:B11');
                $event->sheet->getDelegate()->mergeCells('C10:E10');
                $event->sheet->getDelegate()->mergeCells('F10:F11');

                $totalList = $this->totalList();
                $totalAbsent = $this->totalAbsent();

                $event->sheet->getDelegate()->setCellValue('B'.(11 + $this->index + 1), 'Tổng cộng');
                $event->sheet->getDelegate()->setCellValue('C'.(11 + $this->index + 1), $totalList);
                $event->sheet->getDelegate()->setCellValue('D'.(11 + $this->index + 1), ($totalList - $totalAbsent));
                $event->sheet->getDelegate()->setCellValue('E'.(11 + $this->index + 1), $totalAbsent);

            },

        ];
    }

    public function startRow(): int
    {
        return 12;
    }

    public function countListByTitle($title_id){
       $list = \DB::query()
            ->from('el_quiz_register AS a')
            ->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id')
            ->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code')
            ->where('c.id', '=', $title_id)
            ->where('a.type', '=', 1)
            ->where('a.quiz_id', '=', $this->quiz_id);
       return $list->get();
    }

    public function totalList(){
        $list = \DB::query()
            ->from('el_quiz_register')
            ->where('type', '=', 1)
            ->where('quiz_id', '=', $this->quiz_id)
            ->count('user_id');
        return $list;
    }

    public function totalAbsent(){
        $absent = \DB::query()
            ->from('el_quiz_register AS a')
            ->leftJoin('el_quiz_result AS b', 'b.user_id', '=', 'a.user_id')
            ->where('a.quiz_id', '=', $this->quiz_id)
            ->where('a.type', '=', 1)
            ->whereNull('b.reexamine')
            ->whereNull('b.grade')
            ->count('a.user_id');

        return $absent;
    }

    public function charts()
    {
        $query = $this->query()->get();
        foreach ($query as $item){
            $this->index_chart++;
        }
        $count = $this->index_chart + 1;

        $label = [
            new DataSeriesValues('String','Worksheet!$C$11',null, 1),
            new DataSeriesValues('String','Worksheet!$D$11',null, 1),
            new DataSeriesValues('String','Worksheet!$E$11',null, 1),
        ];

        $categories = [
            new DataSeriesValues('String','Worksheet!$B$12:$B$'.(11 + $count),null, $count)
        ];

        $values = [
            new DataSeriesValues('Number','Worksheet!$C$12:$C$'.(11 + $count),null, $count),
            new DataSeriesValues('Number','Worksheet!$D$12:$D$'.(11 + $count),null, $count),
            new DataSeriesValues('Number','Worksheet!$E$12:$E$'.(11 + $count),null, $count)
        ];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($values) - 1),
            $label,
            $categories,
            $values
        );
        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        $layout = new Layout();
        $layout->setShowVal(true);

        $plot   = new PlotArea($layout, [$series]);
        $legend = new Legend(Legend::POSITION_BOTTOM, null, false);

        $chart  = new Chart(
            'chart BC21',
            new Title('Thống kê thí sinh trong kỳ thi theo chức danh'),
            $legend,
            $plot
        );

        $chart->setTopLeftPosition('H1');
        $chart->setBottomRightPosition('W20');

        return $chart;
    }

    public function drawings()
    {
        $storage = \Storage::disk('upload');
        if ($storage->exists(Config::getConfig('logo'))) {
            $path = $storage->path(Config::getConfig('logo'));
        }else{
            $path = './images/image_default.jpg';
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath($path);
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}
