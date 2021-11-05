<?php
namespace Modules\Report\Export;

use App\Config;
use App\Profile;
use App\Models\Categories\Unit;
use App\Role;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Report\Entities\BC46;
use Modules\Report\Entities\BC47;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC47Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $score = 0;

    public function __construct($param)
    {
        $this->quiz_template_id = $param->quiz_template_id;
    }

    public function query()
    {
        $query = BC47::sql($this->quiz_template_id)->orderBy('id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;
        $question = Question::find($row->question_id);
        $percent_right = number_format(($row->num_correct_answer/$row->num_answer)*100, 2) .'%';
        if ($row->question_type == 'essay'){
            $text_question_type = 'Tự luận';
        }
        if ($row->question_type == 'matching'){
            $text_question_type = 'Nối câu';
        }
        if ($row->question_type == 'fill_in'){
            $text_question_type = 'Điền trống';
        }
        if ($row->question_type == 'multiple-choise'){
            $text_question_type = 'Trắc nghiệm';
        }
        $question_name = trim(htmlspecialchars(strip_tags(@$question->name)), "\xc2\xa0");

        return [
            $this->index,
            $question_name,
            $row->num_question_used,
            $percent_right,
            $text_question_type
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
            ['BÁO CÁO TỈ LỆ TRẢ LỜI ĐÚNG TỪNG CÂU HỎI TRONG NGÂN HÀNG CÂU HỎI'],
            [],
            [
                'STT',
                'Câu hỏi',
                'Số lần sử dụng câu hỏi thi',
                'Tỷ lệ đáp đúng',
                'Loại câu hỏi'
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

                $event->sheet->getDelegate()->mergeCells('A6:E6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:E8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:E'.(8 + $this->index))
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
