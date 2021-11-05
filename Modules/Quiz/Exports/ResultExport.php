<?php
namespace Modules\Quiz\Exports;

use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\WithCharts;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizRank;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizUpdateAttempts;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use Carbon\Carbon;

class ResultExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $res = 0;
    protected $rank = '';
    protected $count = 0;
    protected $count_rank = [];
    protected $result_rank = [];
    protected $temp = '';
    protected $chart_rank = [];
    protected $temp_chart = '';
    protected $index_chart = 0;
    protected $count_title = 18;

    public function __construct($quiz_id, $status, $type, $part, $unit, $title, $result_quiz, $search)
    {
        $this->quiz_id = $quiz_id;
        $this->status = $status;
        $this->type = $type;
        $this->part = $part;
        $this->unit = $unit;
        $this->title = $title;
        $this->result_quiz = $result_quiz;
        $this->search = $search;
    }

    public function map($register): array
    {
        $this->index++;
        $quiz = Quiz::findOrFail($this->quiz_id);
        $quiz_update_attempt_questions = '';
        $quiz_result = $this->getQuizResult($register->user_id, $register->type);
        if ($quiz_result){
            if ($quiz_result->reexamine) {
                $quiz_result->reexamine >= $quiz->pass_score ? $this->res = 1 : $this->res = 0;
                $this->rank = $this->getRank($this->quiz_id, $quiz_result->reexamine);
            }else{
                $quiz_result->grade >= $quiz->pass_score ? $this->res = 1 : $this->res = 0;
                $this->rank = $this->getRank($this->quiz_id, $quiz_result->grade);
            }
            $this->count_rank[] = $this->rank;

            $quiz_attemt_max_id = QuizAttempts::where('user_id',$quiz_result->user_id)->where('quiz_id',$quiz_result->quiz_id)->where('part_id',$register->part_id)->max('id');
            $quiz_attemt = QuizAttempts::find($quiz_attemt_max_id);

            $quiz_start_time = date('Y-m-d H:i:s', $quiz_attemt->timestart);
            $quiz_time_completed = date('Y-m-d H:i:s', $quiz_attemt->timefinish);

            $startTime = Carbon::parse($quiz_start_time);
            $endTime = Carbon::parse($quiz_time_completed);
            $totalDuration =  $startTime->diff($endTime)->format('%H:%I:%S')." giây";

            $quiz_update_attempt_max_id = QuizUpdateAttempts::where('user_id',$quiz_result->user_id)->where('part_id',$register->part_id)->where('quiz_id',$quiz_result->quiz_id)->max('id');
            $quiz_update_attempt = QuizUpdateAttempts::find($quiz_update_attempt_max_id);
            $quiz_update_attempt_questions = $quiz_update_attempt ? json_decode($quiz_update_attempt->questions) : [];
            // dd($quiz_update_attempt_questions);
        }else{
            $this->res = 0;
        }
        $count_quiz_questions = QuizQuestion::where('quiz_id', $this->quiz_id)->count();

        $obj = [];
        $obj[] = $this->index;
        $obj[] = $register->type == 1 ? $register->profile_code : $register->user_secon_code;
        $obj[] = $register->type == 1 ? $register->full_name : $register->secondary_name;
        $obj[] = $register->type == 1 ? 'Thí sinh nội bộ' : 'Thí sinh bên ngoài';
        $obj[] = $register->title_name;
        $obj[] =  $register->unit_name;
        $obj[] = $register->type == 1 ? $register->profile_email : $register->user_secon_email;
        $obj[] = $quiz_result && $quiz_result->timecompleted && $quiz_result->grade > 0 ? 'Đã hoàn thành' : 'Không nộp bài';
        $obj[] = $quiz_result ? Carbon::parse($quiz_start_time)->format('d/m/Y H:i:s') : '';
        $obj[] = $quiz_result ? date('d/m/Y H:i:s', $quiz_result->timecompleted) : '';
        $obj[] = $quiz_result ? $totalDuration : '';
        $obj[] = $quiz_result ? ($quiz_update_attempt ? number_format($quiz_update_attempt->score, 1) : '') : 'Không nộp bài';
        $obj[] = $quiz_result ? ($this->res == 1 ? 'Đậu' : 'Rớt') : 'Không nộp bài';
        $obj[] = $quiz_result ? $this->rank : 'Không nộp bài';

        $counter_correct_answer = 0;
        $counter_wrong_answer = 0;
        if(!empty($quiz_update_attempt_questions)) {
            foreach ($quiz_update_attempt_questions as $key => $item) {
                $obj[] = $item->score == 0 ? '0' : round($item->score,2);
                if($item->score > 0) {
                    $counter_correct_answer += 1;
                } else {
                    $counter_wrong_answer += 1;
                }
            }
            $obj[] = $counter_correct_answer;
            $obj[] = $counter_wrong_answer;

            $percent_correct_answer = ( ($counter_correct_answer * 100)/ $count_quiz_questions );
            $obj[] = round($percent_correct_answer,2) . '%';

            $percent_wrong_answer = ( ($counter_wrong_answer * 100)/ $count_quiz_questions );
            $obj[] = round($percent_wrong_answer,2) . '%';
        }


        if ($this->index == 1){
            $this->temp = $this->rank;
            $this->result_rank[] = $this->rank;
        }

        if ($this->temp != $this->rank && !in_array($this->rank, $this->result_rank) && $this->index > 1){
            $this->result_rank[] = $this->rank;
            $this->temp = $this->rank;
        }

        return $obj;
    }

    public function query(){
        $query = QuizRegister::query();
        $query->select([
            'a.*',
            'b.full_name',
            'b.code AS profile_code',
            'b.email AS profile_email',
            'b.dob AS profile_dob',
            'b.identity_card AS profile_identity_card',
            'b.title_name',
            'b.unit_name',
            'e.name as part_name',
            'e.id as part_id',
            'f.id AS secondary_id',
            'f.name AS secondary_name',
            'f.code AS user_secon_code',
            'f.dob AS user_secon_dob',
            'f.email AS user_secon_email',
            'f.identity_card AS user_secon_identity_card',
        ]);
        $query->from('el_quiz_register AS a');
        $query->leftJoin('el_profile_view AS b', function ($join) {
            $join->on('b.user_id', '=', 'a.user_id')
                ->where('a.type', '=', 1);
        });
        $query->leftJoin('el_quiz_part AS e', 'e.id', '=', 'a.part_id');
        $query->leftJoin('el_quiz_user_secondary AS f', function ($join){
            $join->on('f.id', '=', 'a.user_id')
                ->where('a.type', '=', 2);
        });
        $query->where('a.quiz_id', '=', $this->quiz_id);

        if ($this->search) {
            $query->where(function ($sub_query) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%'. $this->search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $this->search .'%');
                $sub_query->orWhere('f.code', 'like', '%'. $this->search .'%');
                $sub_query->orWhere('f.name', 'like', '%'. $this->search .'%');
            });
        }

        if (!is_null($this->status)) {
            $query->where('b.status', '=', $this->status);
        }

        if ($this->title) {
            $query->where('c.id', '=', $this->title);
        }

        if ($this->unit) {
            $query->where('d.id', '=',  $this->unit);
        }

        if ($this->part) {
            $query->where('e.id', '=',  $this->part);
        }

        if ($this->type) {
            $query->where('a.type', '=',  $this->type);
        }

        if ($this->result_quiz){
            $quizAttempt = QuizAttempts::whereQuizId($this->quiz_id)->where('state', '=', 'completed')->pluck('user_id')->toArray();
            if ($this->result_quiz == 1){
                $query->whereIn('a.user_id', $quizAttempt);
            }

            if ($this->result_quiz == 2){
                $query->whereNotIn('a.user_id', $quizAttempt);
            }

            if ($this->result_quiz == 3){
                $query->where('g.result', '=', 1);
            }

            if ($this->result_quiz == 4){
                $query->where('g.result', '=', 0);
            }

        }

        $query->orderBy('a.id', 'ASC');
        $this->count = $query->count();

        return $query;
    }

    public function headings(): array
    {
        $title_arr[] = 'STT';
        $title_arr[] = 'Mã nhân viên';
        $title_arr[] = 'Họ và tên';
        $title_arr[] = 'Loại';
        $title_arr[] = 'Chức danh';
        $title_arr[] = 'Đơn vị';
        $title_arr[] = 'Email';
        $title_arr[] = 'Trạng thái';
        $title_arr[] = 'Bắt đầu vào lúc';
        $title_arr[] = 'Được hoàn thành';
        $title_arr[] = 'Thời gian thực hiện';
        $title_arr[] = 'Điểm';
        $title_arr[] = 'Đậu/Rớt';
        $title_arr[] = 'Xếp loại';

        $quiz = Quiz::find($this->quiz_id);
        $max_score = QuizQuestion::getTotalScore($quiz->id);
        $score_group = $max_score > 0 ? ($quiz->max_score / $max_score) : 0;

        $quiz_questions = QuizQuestion::where('quiz_id', $this->quiz_id)->get();

        $i = 0;
        foreach($quiz_questions as $quiz_question) {
            $calculate_socre = ($score_group * $quiz_question->max_score);
            $i++;
            $title_arr[] =  'Q.'.$i. '/'. $calculate_socre;
            $this->count_title += 1;
        }

        $title_arr[] = 'Số câu đúng';
        $title_arr[] = 'Số câu sai';
        $title_arr[] = 'TL đúng';
        $title_arr[] = 'TL sai';

        return [
            ['KẾT QUẢ KIỂM TRA KỲ THI'],
            $title_arr,
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
                $char = $this->getChar($this->count_title);
                $event->sheet->getDelegate()->mergeCells('A1:'.$char.'1')->getStyle('A1')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A2:'.$char.''.(2 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);


                // $event->sheet->getDelegate()->getStyle('Q1:R'.(1 + count($this->result_rank)).'')
                //     ->applyFromArray([
                //     'borders' => [
                //         'allBorders' => [
                //             'borderStyle' => Border::BORDER_THIN,
                //         ],
                //     ],
                //     'font' => [
                //         'name' => 'Arial',
                //     ],
                //     'alignment' => [
                //         'horizontal' => Alignment::HORIZONTAL_CENTER,
                //         'vertical'   => Alignment::VERTICAL_CENTER,
                //     ],
                // ]);

                // $event->sheet->getDelegate()->setCellValue('Q1', 'Xếp loại');
                // $event->sheet->getDelegate()->setCellValue('R1', 'Tỷ lệ (%)');

                // $temp = array_count_values($this->count_rank);
                // sort($this->result_rank);
                // if (count($this->result_rank) > 0 && count($temp) > 0) {
                //     foreach ($this->result_rank as $key => $item){
                //         $event->sheet->getDelegate()->setCellValue('Q'.($key + 2), $item);
                //         $event->sheet->getDelegate()->setCellValue('R'.($key + 2), number_format(($temp[$item] / count($this->count_rank)) * 100, 2));
                //     }
                // }
            },

        ];
    }

    public function getRank($quiz_id, $score){
        if ($score) {
            $quiz_rank = QuizRank::where('quiz_id', '=', $quiz_id)
            ->where('score_min', '<=', $score)
            ->where('score_max', '>=', $score)
            ->first(['rank']);
            return $quiz_rank ? $quiz_rank->rank : 'Không xếp loại';
        }
        return  'Không xếp loại';
    }

    public function getQuizResult($user_id, $user_type){
        return QuizResult::where('quiz_id', '=', $this->quiz_id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->first();
    }

//     public function charts()
//     {
//         $query = $this->query();
//         $this->count = $query->count();

//         $quiz_register = $query->get();
//         foreach ($quiz_register as $register){
//             $this->index_chart++;
//             $quiz_result = $this->getQuizResult($register->user_id, $register->type);

//             if ($quiz_result){
//                 if ($quiz_result->reexamine) {
//                     $this->rank = $this->getRank($this->quiz_id, $quiz_result->reexamine);
//                 }else{
//                     $this->rank = $this->getRank($this->quiz_id, $quiz_result->grade);
//                 }
//             }

//             if ($this->index_chart == 1){
//                 $this->temp_chart = $this->rank;
//                 $this->chart_rank[] = $this->rank;
//             }

//             if ($this->temp_chart != $this->rank && !in_array($this->rank, $this->chart_rank) && $this->index_chart > 1){
//                 $this->chart_rank[] = $this->rank;
//                 $this->temp_chart = $this->rank;
//             }
//         }

//         $label = [
//             new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,'Worksheet!$Q$1',null,1)
//         ];

//         $categories = [
//             new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING,'Worksheet!$Q$2:$Q$'.(1 + count($this->chart_rank)).'',null, count($this->chart_rank))
//         ];

//         $values = [
//             new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER,'Worksheet!$R$2:$R$'.(1 + count($this->chart_rank)).'',null, count($this->chart_rank))
//         ];

//         $series = new DataSeries(
//             DataSeries::TYPE_PIECHART,
//             null,
//             range(0, \count($values) - 1),
//             $label,
//             $categories,
//             $values
//         );

//         $layout = new Layout();
// //        $layout->setShowVal(true);
//         $layout->setShowPercent(true);

//         $plot   = new PlotArea($layout, [$series]);
//         $legend = new Legend(Legend::POSITION_BOTTOM, null, false);
//         $chart  = new Chart(
//             'chart 1',
//             new Title('Kết quả kỳ thi'),
//             $legend,
//             $plot
//         );

//         $chart->setTopLeftPosition('T1');
//         $chart->setBottomRightPosition('X12');

//         return $chart;
//     }

    public function getChar($number){
        $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        if ($number > 26){
            $num = floor($number/26);
            $num_1 = $number - ($num * 26);

            $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
        }else{
            $char = $arr_char[($number - 1)];
        }

        return $char;
    }
}
