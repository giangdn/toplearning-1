<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Profile;
use App\Models\Categories\Unit;
use App\Role;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizType;
use Modules\ReportNew\Entities\BC01;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC01Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $score = 0;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->role_id = $param->role_id;
        $this->type_id = $param->quiz_type;
        $this->quiz_id = $param->quiz_id;
    }

    public function query()
    {
        $query = BC01::sql($this->from_date, $this->to_date, $this->type_id, $this->role_id, $this->quiz_id)->orderBy('el_quiz.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;
        $quiz_template = QuizTemplates::find($row->quiz_template_id);
        $role = Role::find($this->role_id);
        $type = QuizType::find($row->type_id);

        $start_date = '';
        $end_date = '';

        $qdate = QuizPart::where('quiz_id', '=', $row->id)
            ->first(\DB::raw('MIN(start_date) as start_date'));
        if ($qdate->exists()) {
            $start_date = $qdate->start_date;
        }

        $qdate = QuizPart::where('quiz_id', '=', $row->id)
            ->first(\DB::raw('MAX(end_date) as end_date'));
        if ($qdate->exists()) {
            $end_date = $qdate->end_date;
        }
        $quiz_result = QuizResult::whereQuizId($row->id)->where('timecompleted', '>', 0)->get();

        $total_score = 0;
        $score_03 = 0;
        $score_35 = 0;
        $score_57 = 0;
        $score_78 = 0;
        $score_89 = 0;
        $score_910 = 0;
        foreach ($quiz_result as $result){
            $total_score += $result->grade;
            if ($result->grade >= 0 && $result->grade < 3){
                $score_03 += 1;
            }
            if ($result->grade >= 3 && $result->grade < 5){
                $score_35 += 1;
            }
            if ($result->grade >= 5 && $result->grade < 7){
                $score_57 += 1;
            }
            if ($result->grade >= 7 && $result->grade < 8){
                $score_78 += 1;
            }
            if ($result->grade >= 8 && $result->grade < 9){
                $score_89 += 1;
            }
            if ($result->grade >= 9){
                $score_910 += 1;
            }
        }
        $num_register = QuizRegister::whereQuizId($row->id)->count();
        $num_doquiz = $quiz_result->count();
        return [
            $this->index,
            $row->name,
            $role ? $role->description : '',
            $type ? $type->name : '',
            $quiz_template ? $quiz_template->name : '',
            QuizQuestion::whereQuizId($row->id)->count(),
            $row->limit_time . ' phút',
            get_date($start_date, 'H:i d/m/Y'),
            $end_date ? get_date($end_date, 'H:i d/m/Y') : '',
            $num_register,
            $num_doquiz,
            ($num_register - $num_doquiz),
            number_format($total_score/($num_doquiz > 0 ? $num_doquiz : 1), 2),
            $score_03,
            $score_35,
            $score_57,
            $score_78,
            $score_89,
            $score_910,
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
            ['BÁO CÁO SỐ LIỆU CÔNG TÁC KHẢO THI'],
            [],
            [
                'STT', 'Thông tin kỳ thi', '', '', '', '', 'Thời gian', '', '', 'Số lượng thí sinh', '', '', 'Điểm trung bình', 'Số lượng thí sinh nằm trong khung điểm', '', '', '', '', ''
            ],
            [
                '',
                'Tên kỳ thi',
                'Phòng phụ trách',
                'Loại hình thi',
                'Đề thi',
                'SL câu hỏi',
                'Thời lượng',
                'Thời gian bắt đầu',
                'Thời gian kết thúc',
                'SL thí sinh đã đăng ký',
                'SL thí sinh thực tế thi',
                'Vắng thi',
                '',
                '[0đ - 3đ)',
                '[3đ - 5đ)',
                '[5đ - 7đ)',
                '[7đ - 8đ)',
                '[8đ - 9đ)',
                '[9đ - 10đ]'
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

                $event->sheet->getDelegate()->mergeCells('A6:S6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:S9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->mergeCells('A8:A9');
                $event->sheet->getDelegate()->mergeCells('B8:F8');
                $event->sheet->getDelegate()->mergeCells('G8:I8');
                $event->sheet->getDelegate()->mergeCells('J8:L8');
                $event->sheet->getDelegate()->mergeCells('M8:M9');
                $event->sheet->getDelegate()->mergeCells('N8:S8');

                $event->sheet->getDelegate()->getStyle('A8:S'.(9 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ]
                    ]);

                $event->sheet->getDelegate()->getStyle('A10:S'.(9 + $this->index))
                    ->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
            },

        ];
    }
    public function startRow(): int
    {
        return 10;
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
