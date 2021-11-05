<?php
namespace Modules\Report\Export;

use App\Config;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizUpdateAttempts;
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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC17ExportSheet1 implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithTitle, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $score = 0;
    protected $score_group = 0;
    protected $count_title = 11;

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
        $profile = Profile::find($report->user_id);
        $arr_unit = Unit::getTreeParentUnit($profile->unit_code);

        $this->index++;
        $status = '';
        $part = QuizPart::where('quiz_id', '=', $this->quiz_id)->where('id', '=', $report->part_id)->first();

        switch ($report->state) {
            case 'inprogress': $status = 'Đang làm bài'; break;
            case 'completed': $status = 'Hoàn thành'; break;
        }

        $obj = [];
        $obj[] = $this->index;
        $obj[] = $report->code;
        $obj[] = $report->lastname .' '. $report->firstname;
        $obj[] = $report->email;
        $obj[] = $report->title_name;
        $obj[] = $arr_unit ? $arr_unit[$profile->unit->level]->name : '';
        $obj[] = $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '';
        $obj[] = $arr_unit ? $arr_unit[2]->name : '';
        $obj[] = $part->name;
        $obj[] = $status;
        $obj[] = $report->sumgrades;

        $update_attempt = QuizUpdateAttempts::where('attempt_id', '=', $report->id)
            ->where('quiz_id', '=', $report->quiz_id)
            ->where('part_id', '=', $report->part_id)
            ->where('user_id', '=', $report->user_id)
            ->where('type', '=', $report->type)
            ->first();

        $score_question = json_decode($update_attempt->questions, true);

        $flag = null;
        foreach ($score_question as $key => $item){
            if ($item['index'] != 0 && $item['qqcategory_id'] > $flag['qqcategory_id']){
                $obj[] = $this->score;
                $obj[] = number_format(($this->score/$this->score_group) * 100,1) .' %';
                $this->score = 0;
                $this->score_group = 0;
            }
            $this->score += number_format($item['score'], 1);
            $this->score_group += number_format($item['score_group'], 1);
            $flag = $item;
        }
        $obj[] = $this->score;
        $obj[] = number_format(($this->score/($this->score_group != 0 ? $this->score_group : 1)) * 100,1) .' %';

        $this->score = 0;
        $this->score_group = 0;
        return $obj;
    }

    public function headings(): array
    {
        $title = [];

        $title[] = 'STT';
        $title[] = 'MSNV';
        $title[] = 'Học viên';
        $title[] = 'Email';
        $title[] = 'Chức danh';
        $title[] = 'Đơn vị trực tiếp';
        $title[] = 'Đơn vị gián tiếp cấp 1';
        $title[] = 'Công ty';
        $title[] = 'Ca thi';
        $title[] = 'Trạng thái';
        $title[] = 'Điểm';

        $question = $this->getQuestion($this->quiz_id);
        foreach ($question as $key => $item){
            if ($item->num_order == $key){
                $title[] = $item->name;
                $title[] = '';

                $this->count_title += 2;
            }
        }

        $start_date = '';
        $end_date = '';
        $quiz = Quiz::find($this->quiz_id);
        $qdate = QuizPart::where('quiz_id', '=', $quiz->id)
            ->first(\DB::raw('MIN(start_date) as start_date'));
        if ($qdate->exists()) {
            $start_date = $qdate->start_date;
        }

        $qdate = QuizPart::where('quiz_id', '=', $quiz->id)
            ->first(\DB::raw('MAX(end_date) as end_date'));
        if ($qdate->exists()) {
            $end_date = $qdate->end_date;
        }
        $register = QuizRegister::where('quiz_id', '=', $quiz->id)->count();
        $result = QuizResult::where('quiz_id', '=', $quiz->id)->count();

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO SỐ LẦN THI THEO NHÓM CÂU HỎI'],
            ['Mã kỳ thi', ($quiz ? $quiz->code : '')],
            ['Tên kỳ thi', ($quiz ? $quiz->name : '')],
            ['Thời gian', ($start_date ? get_date($start_date) .' - '. get_date($end_date) : '')],
            ['Thời gian làm bài', ($quiz ? $quiz->limit_time : '')],
            ['Điểm đạt', ($quiz ? $quiz->pass_score : '')],
            ['Điểm tối đa', ($quiz ? $quiz->max_score : '')],
            ['Số lượng thí sinh', ($quiz ? $register : '')],
            ['Số lượng nộp bài', ($quiz ? $result : '')],
            [],
            $title,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->mergeCells('A6:K6')
                    ->getStyle('A6')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A7:A14')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A7:B14')
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ]
                    ]);

                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                if ($this->count_title > 26){
                    $num = floor($this->count_title/26);
                    $num_1 = $this->count_title - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($this->count_title - 1)];
                }

                $event->sheet->getDelegate()->getStyle('A16:'.$char.(16 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ]
                    ]);

                $event->sheet->getDelegate()->getStyle('A16:'.$char.'16')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');
            },

        ];
    }

    public function startRow(): int
    {
        return 17;
    }

    public function getQuestion($quiz_id){
        $query = QuizQuestion::query()
            ->from('el_quiz_question as a')
            ->leftJoin('el_quiz_question_category AS b', 'b.id', '=', 'a.qqcategory')
            ->where('a.quiz_id', '=', $quiz_id)
            ->where('b.quiz_id', '=', $quiz_id)
            ->groupBy('b.name', 'b.num_order')
            ->get(['b.name', 'b.num_order']);

        return $query;
    }

    public function title(): string
    {
        return 'Sheet1';
    }

    protected function gradeQuestion($question) {
        $score = 0;
        if ($question['type'] == 'multiple-choise') {
            if (isset($question['answer'])){
                $answer_selected = $question['answer'];

                if ($question['multiple'] == 0){
                    $selected_answer = QuestionAnswer::whereIn('id', $answer_selected)
                        ->where('question_id', '=', $question['question_id'])
                        ->where('correct_answer', '=', 1)
                        ->count();

                    $score = ($question['score_group'] * $question['max_score']) * $selected_answer;
                }

                if ($question['multiple'] == 1){
                    $count_answer = QuestionAnswer::where('question_id', '=', $question['question_id'])->count();
                    $correct_answer = QuestionAnswer::where('question_id', '=', $question['question_id'])->where('percent_answer', '>', 0)->count();
                    $selected = QuestionAnswer::where('question_id', '=', $question['question_id'])
                        ->whereIn('id', $answer_selected)->get();

                    if ($selected->count() == $count_answer && $correct_answer < $count_answer){
                        $score = 0;
                    }else{
                        $score = 0;
                        foreach ($selected as $item){
                            $score += (($question['score_group'] * $question['max_score']) * $item->percent_answer ) / 100;
                        }
                    }
                }
            }else{
                $score = 0;
            }
        }
        if ($question['type'] == 'matching'){

            if ($question['matching']){
                $matching_select = $question['matching'];

                $answers = QuestionAnswer::where('question_id', '=', $question['question_id'])->get();
                $count = 0;
                foreach ($answers as $answer){
                    if ($matching_select->{$answer->id} == $answer->matching_answer){
                        $count += 1;
                    }
                }
                if ($count == $answers->count()){
                    $score = ($question['score_group'] * $question['max_score']);
                }
            }
        }

        $question['score'] = $score;
        return $score;
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
