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
use Maatwebsite\Excel\Concerns\WithTitle;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRank;
use Modules\Report\Entities\BC22;
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
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpParser\Node\Stmt\Label;

class BC22ExportSheet1 implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithTitle, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count_title = 0;
    protected $char = 'D';

    public function __construct($quiz_id, $from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->quiz_id = $quiz_id;
    }

    public function query()
    {
        $query = BC22::sql($this->quiz_id, $this->from_date, $this->to_date)->orderBy('c.id', 'ASC');
        return $query;
    }

    public function map($report): array
    {
        $obj = [];

        $this->index++;
        $list = $this->countListByTitle($report->title_id);
        $ranks = QuizRank::where('quiz_id', '=', $this->quiz_id)->get();
        $results = $this->getResultByTitle($report->title_id);

        $obj[] = $this->index;
        $obj[] = $report->title_name;
        $obj[] = $list;
        $obj[] = '100%';

        foreach ($ranks as $key => $rank){
            if ($results){
                $count = 0;
                foreach ($results as $result){
                    if ($result->reexamine){
                        if ($result->reexamine >= $rank->score_min && $result->reexamine <= $rank->score_max){
                            $count++;
                        }
                    }else{
                        if ($result->grade >= $rank->score_min && $result->grade <= $rank->score_max){
                            $count++;
                        }
                    }
                }
            }else{
                $count = 0;
            }
            $obj[] = $count;
            $obj[] = number_format(($count / $list) * 100, 1) .' %';
        }

        return $obj;
    }

    public function headings(): array
    {
        $ranks = QuizRank::where('quiz_id', '=', $this->quiz_id)->get();

        $title = [];
        $cate = [];

        $title[] = 'STT';
        $title[] = 'Chức danh';
        $title[] = 'Tham gia sát hạch';
        $title[] = 'Tỷ lệ';

        $cate[] = '';
        $cate[] = '';
        $cate[] = '';
        $cate[] = '';

        foreach ($ranks as $rank){
            $title[] = 'Loại ' . $rank->rank . PHP_EOL .'(' . number_format($rank->score_max, 1) . ' >= Điểm >= ' . number_format($rank->score_min, 1) .')';
            $title[] = '';

            $cate[] = 'Số lượng';
            $cate[] = 'Tỷ lệ';

            $this->count_title++;
        }
        $quiz = Quiz::find($this->quiz_id);
        return [
            [],
            [],
            [],
            [],
            [],
            [],
            ['THỐNG KÊ TỶ LỆ XẾP LOẠI TRONG KỲ THI THEO CHỨC DANH'],
            [($quiz ? $quiz->name : '')],
            ['Từ '. $this->from_date. ' đến '. $this->to_date],
            [],
            $title,
            $cate
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

                $event->sheet->getDelegate()->mergeCells('A11:A12');
                $event->sheet->getDelegate()->mergeCells('B11:B12');
                $event->sheet->getDelegate()->mergeCells('C11:C12');
                $event->sheet->getDelegate()->mergeCells('D11:D12');

                $this->char = chr(ord($this->char) + ($this->count_title * 2));

                $event->sheet->getDelegate()->getStyle('A11:'.$this->char.(12 + $this->index + 1))->applyFromArray([
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

                $total_list = $this->totalListByTitle();
                $event->sheet->getDelegate()->setCellValue('B'.(12 + $this->index + 1), 'Tổng cộng');
                $event->sheet->getDelegate()->setCellValue('C'.(12 + $this->index + 1), $total_list);
                $event->sheet->getDelegate()->setCellValue('D'.(12 + $this->index + 1), '100%');

                $ranks = QuizRank::where('quiz_id', '=', $this->quiz_id)->get();
                $query = $this->query()->get();
                $char = 'D';
                foreach ($ranks as $key => $rank){
                    $total = 0;
                    foreach ($query as $item){
                        $results = $this->getResultByTitle($item->title_id);
                        $count = 0;
                        foreach ($results as $result){
                            if ($result->reexamine){
                                if ($result->reexamine >= $rank->score_min && $result->reexamine <= $rank->score_max){
                                    $count++;
                                }
                            }else{
                                if ($result->grade >= $rank->score_min && $result->grade <= $rank->score_max){
                                    $count++;
                                }
                            }
                        }
                        $total += $count;
                    }

                    if ($key > 0){
                        $char = chr(ord($char) + 2);
                    }else{
                        $char = chr(ord($char) + 1);
                    }
                    $char1 = chr(ord($char) + 1);

                    $event->sheet->getDelegate()->mergeCells($char.'11:'.$char1.'11')->getStyle($char.'11')
                        ->getAlignment()
                        ->setWrapText(true);

                    $event->sheet->getDelegate()->getRowDimension(11)->setRowHeight(39);

                    $event->sheet->getDelegate()->setCellValue($char.''.(12 + $this->index + 1), $total);
                    if ($total_list == 0) {
                        $total_list = 1;
                    }
                    $event->sheet->getDelegate()->setCellValue($char1.''.(12 + $this->index + 1), number_format(($total/$total_list) * 100, 1) .' %' );
                }
            },

        ];
    }

    public function startRow(): int
    {
        return 13;
    }

    public function countListByTitle($title_id){
       $list = \DB::query()
            ->from('el_quiz_register AS a')
            ->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id')
            ->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code')
            ->where('c.id', '=', $title_id)
            ->where('a.quiz_id', '=', $this->quiz_id)
            ->count('a.user_id');
       return $list;
    }

    public function totalListByTitle(){
        $list = \DB::query()
            ->from('el_quiz_register AS a')
            ->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id')
            ->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code')
            ->where('a.quiz_id', '=', $this->quiz_id)
            ->count('a.user_id');
        return $list;
    }

    public function getResultByTitle($title_id){
        $results = \DB::query()
            ->from('el_quiz_result AS b')
            ->leftJoin('el_profile AS c', 'c.user_id', '=', 'b.user_id')
            ->leftJoin('el_titles AS d', 'd.code', '=', 'c.title_code')
            ->where('d.id', '=', $title_id)
            ->where('b.quiz_id', '=', $this->quiz_id)
            ->get(['b.reexamine', 'b.grade']);

        return $results;
    }

    public function title(): string
    {
        return 'Sheet 1';
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
