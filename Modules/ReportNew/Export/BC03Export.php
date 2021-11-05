<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\ReportNew\Entities\BC03;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC03Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $score = 0;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->quiz_template_id = $param->quiz_template_id;
    }

    public function query()
    {
        $query = BC03::sql($this->from_date, $this->to_date, $this->quiz_template_id)->orderBy('c.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;
        $num_question_used = QuizQuestion::whereQuizId($row->id)
            ->where(function ($sub) use ($row){
                $sub->orWhere('qcategory_id', '=', $row->cate_ques_id);
                $sub->orWhereIn('question_id', function ($sub) use ($row){
                    $sub->select(['id'])
                        ->from('el_question')
                        ->where('category_id', '=', $row->cate_ques_id)
                        ->pluck('id')
                        ->toArray();
                });
            })
            ->count();
        $num_list_question = Question::whereStatus(1)->whereCategoryId($row->cate_ques_id)->count();

        $total_question = 0;
        $total_question_right = 0;

        $update_attemplate = QuizUpdateAttempts::whereQuizId($row->id)->get();
        foreach ($update_attemplate as $item){
            $questions = json_decode($item['questions'], true);
            foreach ($questions as $question){
                if ($question['score'] == $question['score_group'] && $question['category_id'] == $row->cate_ques_id){
                    $total_question_right += 1;
                }
            }
            $total_question += count($questions);
        }
        if($total_question > 0 &&  $total_question_right > 0) {
            $percent_right = number_format(($total_question_right/$total_question)*100, 2) .'%';
        } else {
            $percent_right = '0%';
        }

        return [
            $this->index,
            $row->quiz_name,
            $row->cate_ques_name,
            $num_question_used,
            $num_list_question,
            $percent_right
        ];
    }

    public function headings(): array
    {
        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO CƠ CẤU ĐỀ THI'],
            [],
            [
                'STT',
                'Tên kỳ thi',
                'Danh mục câu hỏi',
                'SL câu hỏi được sử dụng',
                'SL câu hỏi trong Ngân hàng câu hỏi',
                'Tỷ lệ đáp đúng',
            ]
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

                $event->sheet->getDelegate()->mergeCells('A6:F6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:F8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:F'.(8 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
            },

        ];
    }
    public function startRow(): int
    {
        return 9;
    }

    public function drawings()
    {
        $storage = \Storage::disk('upload');

        LogoModel::addGlobalScope(new CompanyScope());
        $logo = LogoModel::where('status',1)->first();
        if ($logo) {
            $path = $storage->path($logo->image);
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
