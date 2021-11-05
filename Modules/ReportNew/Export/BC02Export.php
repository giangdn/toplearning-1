<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\Area;
use App\Profile;
use App\Role;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\ReportNew\Entities\BC02;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC02Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
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
        $query = BC02::sql($this->from_date, $this->to_date, $this->type_id, $this->role_id, $this->quiz_id)->orderBy('el_quiz_attempts.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;
        $quiz = Quiz::find($row->quiz_id);
        $quiz_template = QuizTemplates::find($quiz->quiz_template_id);
        $role = Role::find($this->role_id);
        $type = QuizType::find($row->type_id);

        $unit_name = '';
        $unit_parrent_name = '';
        $title_name = '';
        $area_name = '';
        if ($row->type == 1){
            $profile = Profile::query()->find($row->user_id);
            $full_name = $profile->getFullName();
            $unit_name = @$profile->unit->name;
            $unit_parrent_name = @$profile->unit->parent->name;
            $title_name = @$profile->titles->name;

            $area = Area::find(@$profile->unit->area_id);
            $area_name = @$area->name;
        }else{
            $profile = QuizUserSecondary::find($row->user_id);
            $full_name = @$profile->name;
        }

        return [
            $this->index,
            $quiz->name,
            $role ? $role->description : '',
            $type ? $type->name : '',
            $quiz_template ? $quiz_template->name : '',
            $full_name,
            @$profile->code,
            $area_name,
            $unit_name,
            $unit_parrent_name,
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
                'Khu vực',
                'Đơn vị trực tiếp',
                'Đơn vị quản lý',
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

                $event->sheet->getDelegate()->mergeCells('A6:O6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:O8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:O'.(8 + $this->index))
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
