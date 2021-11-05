<?php
namespace Modules\Report\Export;

use App\Config;
use App\Profile;
use App\Models\Categories\TrainingProgram;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyQuestionAnswer;
use Modules\Survey\Entities\SurveyQuestionCategory;
use Modules\Survey\Entities\SurveyUser;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC28Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $total = 0;

    public function __construct($param)
    {
        $this->survey_id = $param->survey_id;
    }

    public function query()
    {
        $query = \DB::query();
        $query->select([
            'a.id',
            'a.name',
        ])
            ->from('el_survey_template_question_category as a')
            ->leftJoin('el_survey as b', 'b.template_id', '=', 'a.template_id')
            ->where('b.id', '=', $this->survey_id)
            ->orderBy('a.id', 'ASC');

        return $query;
    }
    public function map($report): array
    {
        $this->total++;
        $result = [
            [ $report->name ]
        ];
        $questions = $this->getQuestion($report->id);
        foreach ($questions as $question){
            $this->total++;

            if ($question->type == 'essay'){
                $answer_query = \DB::query()
                    ->from('el_survey_user_question AS a')
                    ->join('el_survey_user_category AS c', 'c.id', '=', 'a.survey_user_category_id')
                    ->join('el_survey_user AS d', 'd.id', '=', 'c.survey_user_id')
                    ->where('a.question_id', '=', $question->id)
                    ->where('d.survey_id', '=', $this->survey_id)
                    ->pluck('a.answer_essay')
                    ->toArray();

                $ans = implode('; ', $answer_query);
            }else{
                $ans = 'Số lượng';
            };

            $result[] = [
                str_repeat(' ', 5) . $question->name,
                $ans
            ];

            $answers = $this->getAnswer($question->id);
            foreach ($answers as $answer){
                $this->total++;

                $count_answer = \DB::query()
                    ->select(['b.answer_name', 'b.text_answer', 'b.is_text'])
                    ->from('el_survey_user_question AS a')
                    ->join('el_survey_user_answer AS b', 'b.survey_user_question_id', '=', 'a.id')
                    ->join('el_survey_user_category AS c', 'c.id', '=', 'a.survey_user_category_id')
                    ->join('el_survey_user AS d', 'd.id', '=', 'c.survey_user_id')
                    ->where('a.question_id', '=', $question->id)
                    ->where('d.survey_id', '=', $this->survey_id)
                    ->where('b.is_check', '=', 1)
                    ->where('b.answer_id', '=', $answer->id)
                    ->count();

                $result[] = [
                     str_repeat(' ', 10) . $answer->name,
                     $count_answer ? $count_answer : '0',
                ];
            }
        }

        return $result;

    }
    public function headings(): array
    {
        $survey = Survey::find($this->survey_id);
        $count_object = Profile::leftJoin('el_titles AS b', 'b.code', '=', 'title_code')
            ->leftJoin('el_unit AS c', 'c.code', '=', 'unit_code')
            ->whereIn('user_id', function ($subquery) {
                $subquery->select(['user_id']);
                $subquery->from('el_survey_object');
                $subquery->where('survey_id', '=', $this->survey_id);
            })
            ->orWhereIn('b.id', function ($subquery) {
                $subquery->select(['title_id']);
                $subquery->from('el_survey_object');
                $subquery->where('survey_id', '=', $this->survey_id);
            })
            ->orWhereIn('c.id', function ($subquery) {
                $subquery->select(['unit_id']);
                $subquery->from('el_survey_object');
                $subquery->where('survey_id', '=', $this->survey_id);
            })->count();

        $count_survey_user = SurveyUser::where('survey_id', '=',$this->survey_id)->count();

        return [
            [],
            [],
            [],
            [],
            [],
            [],
            ['THỐNG KÊ KẾT QUẢ KHẢO SÁT'],
            ['Tên khảo sát: '. $survey->name],
            ['Từ '. get_date($survey->start_date, 'd/m/Y'). ($survey->end_date ? ' đến '. get_date($survey->end_date, 'd/m/Y') : '')],
            ['Tham gia / đối tượng: '. $count_survey_user .'/'. $count_object],
            [' '],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'bold'      =>  true,
                        'name'      => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->mergeCells('A7:B7')->getStyle('A7')->applyFromArray($title);
                $event->sheet->getDelegate()->getStyle('A12:B'.(11 + $this->total))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('B12:B'.(11 + $this->total))->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::HORIZONTAL_LEFT,
                    ],
                ]);

                $event->sheet->getDelegate()->getStyle('A12:A'.(11 + $this->total))->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::HORIZONTAL_CENTER
                    ],
                ]);

            },
        ];
    }
    public function startRow(): int
    {
        return 12;
    }

    public function getQuestion($cate_id){
        $question = SurveyQuestion::where('category_id', '=', $cate_id)->get();
        return $question;
    }

    public function getAnswer($ques_id){
        $answer = SurveyQuestionAnswer::where('question_id', '=', $ques_id)->get();
        return $answer;
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
