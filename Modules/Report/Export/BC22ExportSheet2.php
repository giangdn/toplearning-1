<?php
namespace Modules\Report\Export;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;
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
use PhpParser\Node\Stmt\Label;

class BC22ExportSheet2 implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithTitle, WithCharts
{
    use Exportable;
    protected $index = 0;
    protected $count_title = 0;
    protected $char = 'B';
    protected $index_chart = 0;

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
        $ranks = QuizRank::where('quiz_id', '=', $this->quiz_id)->get();
        $results = $this->getResultByTitle($report->title_id);

        $obj[] = $this->index;
        $obj[] = $report->title_name;

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
        }

        return $obj;
    }

    public function headings(): array
    {
        $ranks = QuizRank::where('quiz_id', '=', $this->quiz_id)->get();

        $title = [];
        $title[] = 'STT';
        $title[] = 'Chức danh';

        foreach ($ranks as $rank){
            $title[] = 'Loại ' . $rank->rank;
            $this->count_title++;
        }

        return [
            $title,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $this->char = chr(ord($this->char) + $this->count_title);
                $event->sheet->getDelegate()->getStyle('A1:'.$this->char.''.(1 + $this->index + 1))->applyFromArray([
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
                $event->sheet->getDelegate()->setCellValue('B'.(1 + $this->index + 1), 'Tổng cộng');
                $event->sheet->getDelegate()->setCellValue('C'.(1 + $this->index + 1), $total_list);

                $ranks = QuizRank::where('quiz_id', '=', $this->quiz_id)->get();
                $query = $this->query()->get();
                $char = 'B';
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

                    $char = chr(ord($char) + 1);
                    $event->sheet->getDelegate()->setCellValue($char.''.(1 + $this->index + 1), $total);
                }
            },

        ];
    }

    public function startRow(): int
    {
        return 2;
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

    public function charts()
    {
        $count_rank = 0;
        $label = [];
        $values = [];
        $query = $this->query()->get();
        foreach ($query as $item){
            $this->index_chart++;
        }
        $count = $this->index_chart + 1;

        $ranks = QuizRank::where('quiz_id', '=', $this->quiz_id)->get();
        $char = 'B';
        foreach ($ranks as $rank){
            $count_rank++;
            $char = chr(ord($char) + 1);
            $label[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,'Sheet2!$'.$char.'$1',null, 1);
            $values[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER,'Sheet2!$'.$char.'$2:$'.$char.'$'.(1 + $count),null, $count);
        }

        $categories = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,'Sheet2!$B$2:$B$'.(1 + $count),null, $count)
        ];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_STANDARD,
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
            'chart 3',
            new Title('Thống kê tỷ lệ xếp loại trong kỳ thi theo chức danh'),
            $legend,
            $plot
        );

        $char1 = chr(ord($char) + ($count_rank + 1));

        $chart->setTopLeftPosition($char1.'1');
        $chart->setBottomRightPosition('W20');

        return $chart;
    }

    public function title(): string
    {
        return 'Sheet2';
    }
}
