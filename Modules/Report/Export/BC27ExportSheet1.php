<?php
namespace Modules\Report\Export;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizPart;
use Modules\Report\Entities\BC27;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class BC27ExportSheet1 implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithTitle
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $score = 0;
    protected $count_title = 0;
    protected $char = 'I';

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

        $this->count = $query->count();
        return $query;
    }

    public function map($report): array
    {
        $quiz = Quiz::find($this->quiz_id);
        $this->index++;
        $quiz_attempt = $this->getStatus($this->user_id, $this->quiz_id);

        $status = '';
        switch ($quiz_attempt->state) {
            case 'inprogress': $status = 'Đang làm bài'; break;
            case 'completed': $status = 'Hoàn thành'; break;
        }

        $obj = [];
        $obj[] = $this->index;
        $obj[] = $report->code;
        $obj[] = $report->lastname .' '. $report->firstname;
        $obj[] = $report->email;
        $obj[] = $report->unit_name;
        $obj[] = $report->title_name;
        $obj[] = $report->part_name;
        $obj[] = $status;
        $obj[] = number_format($report->grade, 1);

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
            $question = $this->getQuestion($this->quiz_id);
            $ii = 1;
            foreach ($question as $key => $item){
                if ($item->num_order == $key){
                    $obj[] = array_sum(array_column($arr, $ii))/$attempt->count();
                    $ii++;
                }
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
        $title[] = 'Email';
        $title[] = 'Đơn vị';
        $title[] = 'Chức danh';
        $title[] = 'Ca thi';
        $title[] = 'Trạng thái';
        $title[] = 'Điểm';

        $question = $this->getQuestion($this->quiz_id);
        foreach ($question as $key => $item){
            if ($item->num_order == $key){
                $title[] = $item->name;
                $this->count_title++;
            }
        }

        return [
            ['BÁO CÁO QUẢ THI THEO NHÓM CÂU HỎI'],
            $title,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:I1')->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'size' =>  12,
                        'bold' =>  true,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $this->char = chr(ord($this->char) + ($this->count_title));

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

    public function getStatus($user_id, $quiz_id){
        $query = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->groupBy(['state'])
            ->select('state');

        return $query->first();
    }

    public function title(): string
    {
        return 'Sheet1';
    }
}