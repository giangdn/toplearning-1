<?php
namespace Modules\Report\Export;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizPart;
use Modules\Report\Entities\BC17;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class BC17ExportSheet2 implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithStartRow, WithTitle, WithCharts
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $score = 0;
    protected $score_group = 0;
    protected $count_title = 0;
    protected $char = 'C';

    public function __construct($quiz_id, $from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->quiz_id = $quiz_id;
    }

    public function query()
    {
        $query = BC17::sql($this->quiz_id, $this->from_date, $this->to_date)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    
    public function map($report): array
    {
        $this->index++;

        $obj = [];
        $obj[] = $this->index;
        $obj[] = $report->code;
        $obj[] = $report->lastname .' '. $report->firstname;

        $query = \DB::query();
        $query->select(['a.score', 'a.qqcategory_id', 'a.score_group'])
            ->from('el_quiz_template_question AS a')
            ->leftJoin('el_quiz_attempts_template AS b', 'b.template_id', '=','a.template_id')
            ->where('b.attempt_id', '=', $report->id);
        $score_question = $query->get();

        foreach ($score_question as $key => $item){
            if ($key != 0 && $item->qqcategory_id > $score_question[$key-1]->qqcategory_id){
                $obj[] = $this->score;
                $this->score = 0;
            }
            $this->score += number_format($item->score, 1);
        }
        $obj[] = $this->score;
        $this->score = 0;

        return $obj;
    }

    public function headings(): array
    {
        $title = [];

        $title[] = 'STT';
        $title[] = 'MSNV';
        $title[] = 'Học viên';

        $question = $this->getQuestion($this->quiz_id);
        foreach ($question as $key => $item){
            if ($item->num_order == $key){
                $title[] = $item->name;
                $this->count_title++;
            }
        }

        return [
            ['Kết quả thi theo nhóm câu hỏi'],
            $title,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:F1')->getStyle('A1')->applyFromArray([
                    'font' => [
                        'size' =>  12,
                        'bold' =>  true,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $this->char = chr(ord($this->char) + $this->count_title);

                $event->sheet->getDelegate()->getStyle('A2:'.$this->char.''.(2 + $this->count))->applyFromArray([
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
            },

        ];
    }

    public function startRow(): int
    {
        return 2;
    }

    public function getQuestion($quiz_id){
        $query = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->leftJoin('el_quiz_attempts_template AS b', 'b.attempt_id', '=', 'id')
            ->leftJoin('el_quiz_template_question AS c', 'c.template_id', '=', 'b.template_id')
            ->leftJoin('el_quiz_template_question_category AS d', 'd.id', '=', 'c.qqcategory_id')
            ->groupBy('c.qindex', 'c.score_group', 'd.name', 'd.num_order')
            ->get(['c.qindex', 'c.score_group', 'd.name', 'd.num_order']);

        return $query;
    }

    public function title(): string
    {
        return 'Sheet2';
    }

    public function charts()
    {
        $label = [];
        $categories = [];
        $values = [];
        $char = 'D';

        $count_title = 0;
        $query = $this->query()->get();

        $question = $this->getQuestion($this->quiz_id);
        foreach ($question as $key => $item){
            if ($item->num_order == $key){
                $count_title++;
                if ($key > 0){
                    $char = chr(ord($char) + 1);
                }
            }
        }

        foreach ($query as $key => $item){
            $label[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,'Sheet2!$C$'.(3 + $key),null, 1);
            $categories[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,'Sheet2!$D$2:$'.$char.'$2',null, $count_title);
            $values[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER,'Sheet2!$D$'.(3 + $key).':$'.$char.'$'.(3 + $key),null, $count_title);
        }

        $series = new DataSeries(
            DataSeries::TYPE_RADARCHART,
            null,
            range(0, \count($values) - 1),
            $label,
            $categories,
            $values,
            null,
            null,
            DataSeries::STYLE_MARKER
        );

        $plot   = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_TOP, null, false);
        $chart  = new Chart(
            'chart 2',
            new Title('Kết quả kỳ thi theo nhóm câu hỏi'),
            $legend,
            $plot
        );

        $char1 = chr(ord($char) + 2);
        $char2 = chr(ord($char1) + 5);

        $chart->setTopLeftPosition($char1.'1');
        $chart->setBottomRightPosition($char2.'20');

        return $chart;
    }
}