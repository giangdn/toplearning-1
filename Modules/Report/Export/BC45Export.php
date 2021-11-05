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
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Report\Entities\BC45;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC45Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
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
        $this->type_id = $param->type_id;
    }

    public function query()
    {
        $query = BC45::sql($this->from_date, $this->to_date, $this->type_id, $this->role_id)->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;
        $quiz = Quiz::find($row->quiz_id);
        $quiz_template = QuizTemplates::find($quiz->quiz_template_id);
        $role = Role::find($this->role_id);
        $type = QuizType::find($this->type_id);

        $unit_name = '';
        $title_name = '';
        if ($row->type == 1){
            $profile = Profile::query()->find($row->user_id);
            $full_name = $profile->getFullName();
            $unit_name = @$profile->unit->name;
            $title_name = @$profile->titles->name;
        }else{
            $profile = QuizUserSecondary::find($row->user_id);
            $full_name = $profile->name;
        }

        return [
            $this->index,
            $quiz->name,
            $role ? $role->description : '',
            $type ? $type->name : '',
            $quiz_template ? $quiz_template->name : '',
            $full_name,
            @$profile->code,
            $unit_name,
            $title_name,
            @$profile->email,
            date('H:i:s d/m/Y', $row->timestart),
            $row->timefinish > 0 ? calculate_time_span($row->timestart, $row->timefinish) : '',
            $row->sumgrades,
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
            ['BÁO CÁO SỐ LIỆU ĐIỂM THI CHI TIẾT'],
            [],
            [
                'STT',
                'Tên kỳ thi',
                'Phòng phụ trách',
                'Loại hình thi',
                'Đề thi',
                'Họ tên',
                'MSNV',
                'Đơn vị',
                'Chức danh',
                'Email',
                'Thời gian bắt đầu',
                'Thời gian thực hiện',
                'Điểm/10',
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

                $event->sheet->getDelegate()->mergeCells('A6:M6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:M8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:M'.(8 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ]
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
