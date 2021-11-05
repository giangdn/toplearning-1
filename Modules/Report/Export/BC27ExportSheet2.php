<?php
namespace Modules\Report\Export;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizResult;
use Modules\Report\Entities\BC17;
use Modules\Report\Entities\BC27;
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

class BC27ExportSheet2 implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithStartRow, WithTitle, WithCharts
{
    use Exportable;
    protected $index = 0;
    protected $score = 0;
    protected $score_group = [];
    protected $count_title = 0;
    protected $char = 'C';

    public function __construct($quiz_id, $from_date, $to_date, $user_id)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->quiz_id = $quiz_id;
        $this->user_id = $user_id;
    }

    public function query()
    {
        $query = BC27::sql($this->quiz_id, $this->from_date, $this->to_date, $this->user_id)->orderBy('id', 'ASC');
        return $query;
    }
    public function map($report): array
    {
        $quiz = Quiz::find($this->quiz_id);
        $this->index++;

        $obj = [];
        $obj[] = $this->index;
        $obj[] = $report->code;
        $obj[] = $report->lastname .' '. $report->firstname;

        $attempt = $this->getAttempt($this->quiz_id, $this->user_id);
        if ($quiz->grade_methor == 2){
            $arr = [];
            foreach ($attempt as $key_att => $attemp){
                $i = 1;
                $score_question = $this->getScoreByGroup($attemp->id);
                foreach ($score_question as $key => $item){
                    if ($key != 0 && $item->qqcategory_id > $score_question[$key-1]->qqcategory_id){
                        $arr[] = [$i => $this->score];
                        $this->score = 0;
                        $i++;
                    }
                    $this->score += number_format($item->score, 1);
                }
                $arr[] = [$i => $this->score];
                $this->score = 0;
            }
            for ($ii = 1; $ii <= $this->count_title; $ii++){
                $obj[] = array_sum(array_column($arr, $ii))/$attempt->count();
            }
        }else{
            $score_question = $this->getScoreByGroup($attempt->id);
            foreach ($score_question as $key => $item){
                if ($key != 0 && $item->qqcategory_id > $score_question[$key-1]->qqcategory_id){
                    $obj[] = $this->score;

                    $this->score = 0;
                }
                $this->score += number_format($item->score, 1);
            }
            $obj[] = $this->score;
            $this->score = 0;
        }
        return $obj;
    }

    public function headings(): array
    {
        $title = [];
        $title[] = 'STT';
        $title[] = 'MSNV';
        $title[] = 'Học viên';

        $total = 0;
        $question = $this->getQuestion($this->quiz_id);
        foreach ($question as $key => $item){
            if ($item->num_order == $key){
                $title[] = $item->name;
                $this->count_title++;
            }
            if ($key != 0 && $item->num_order > $question[$key-1]->num_order){
                $this->score_group[] = $total;
                $total = 0;
            }
            $total += number_format($item->score_group, 1);
        }
        $this->score_group[] = $total;

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
                $event->sheet->getDelegate()->setCellValue('C4', 'Điểm tối đa');
                $event->sheet->getDelegate()->setCellValue('C5', 'Điểm Trung Bình');
                $char = 'C';
                foreach ($this->score_group as $key => $item){
                    $char1 = chr(ord($char) + ($key+1));
                    $event->sheet->getDelegate()->setCellValue($char1.'4', $item);
                }
                $DTB = $this->getDTB();
                foreach ($DTB as $key => $dtb){
                    $char2 = chr(ord($char) + ($key+1));
                    $event->sheet->getDelegate()->setCellValue($char2.'5', $dtb);
                }

                $this->char = chr(ord($this->char) + $this->count_title);
                $event->sheet->getDelegate()->getStyle('A2:'.$this->char.'5')->applyFromArray([
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

    public function getScoreByGroup($attemp_id){
        $query = DB::query();
        $query->select(['a.score', 'a.qqcategory_id', 'a.score_group'])
            ->from('el_quiz_template_question AS a')
            ->leftJoin('el_quiz_attempts_template AS b', 'b.template_id', '=','a.template_id')
            ->where('b.attempt_id', '=', $attemp_id);
        return $query->get();
    }

    public function getAttempt($quiz_id, $user_id){
        $quiz = Quiz::find($quiz_id);
        //lần cao nhất
        if ($quiz->grade_methor == 1){
            $quiz_attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('sumgrades', '=', function($sub) use($quiz_id, $user_id){
                    $sub->select(\DB::raw('MAX(sumgrades)'))
                        ->from('el_quiz_attempts')
                        ->where('quiz_id', '=', $quiz_id)
                        ->where('user_id', '=', $user_id)
                        ->first();
                })->first();
        }
        //ĐTB
        if ($quiz->grade_methor == 2){
            $quiz_attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)->get();
        }
        //Lần thi đầu
        if ($quiz->grade_methor == 3){
            $quiz_attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('attempt', '=', 1)
                ->first();
        }
        //Lần thi cuối
        if ($quiz->grade_methor == 4) {
            $quiz_attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
                ->where('user_id', '=', $user_id)
                ->where('attempt', '=', function ($subquery) use ($quiz_id, $user_id) {
                    $subquery->select(\DB::raw('MAX(attempt)'))
                        ->from('el_quiz_attempts')
                        ->where('quiz_id', '=', $quiz_id)
                        ->where('user_id', '=', $user_id)
                        ->first();
                })->first();
        }
        return $quiz_attempt;
    }

    public function getDTB(){
        $user = [];
        $obj = [];
        $count = 0;
        $quiz_result = QuizResult::where('quiz_id', '=', $this->quiz_id)->get();
        foreach ($quiz_result as $result){
            $user[] = $this->getScoreUser($result->quiz_id, $result->user_id);
            $count++;
        }
        for ($ii = 0; $ii < $this->count_title; $ii++){
            $obj[] = array_sum(array_column($user, $ii))/$count;
        }

        return $obj;
    }

    public function getScoreUser($quiz_id, $user_id){
        $attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)->get();
        $obj = [];
        $arr = [];
        foreach ($attempt as $key_att => $attemp){
            $i = 1;
            $score_question = $this->getScoreByGroup($attemp->id);
            foreach ($score_question as $key => $item){
                if ($key != 0 && $item->qqcategory_id > $score_question[$key-1]->qqcategory_id){
                    $arr[] = [$i => $this->score];
                    $this->score = 0;
                    $i++;
                }
                $this->score += number_format($item->score, 1);
            }
            $arr[] = [$i => $this->score];
            $this->score = 0;
        }
        for ($ii = 1; $ii <= $this->count_title; $ii++){
            $obj[] = array_sum(array_column($arr, $ii))/$attempt->count();
        }

        return $obj;
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
        $question = $this->getQuestion($this->quiz_id);
        foreach ($question as $key => $item){
            if ($item->num_order == $key){
                $count_title++;
                if ($key > 0){
                    $char = chr(ord($char) + 1);
                }
            }
        }

        for ($i = 0; $i < 3; $i++){
            $label[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,'Sheet2!$C$'.(3 + $i),null, 1);
            $categories[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,'Sheet2!$D$2:$'.$char.'$2',null, $count_title);
            $values[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER,'Sheet2!$D$'.(3 + $i).':$'.$char.'$'.(3 + $i),null, $count_title);
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
        $char2 = chr(ord($char1) + 7);

        $chart->setTopLeftPosition($char1.'1');
        $chart->setBottomRightPosition($char2.'20');

        return $chart;
    }
}