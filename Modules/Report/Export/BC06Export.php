<?php
namespace Modules\Report\Export;
use App\Config;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Report\Entities\BC06;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC06Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    private $from_date;
    private $to_date;
    private $teacher;
    private $teacher_type;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->teacher = $param->teacher;
        $this->teacher_type = $param->teacher_type;
    }

    public function query()
    {
        $query = BC06::sql($this->from_date, $this->to_date, $this->teacher, $this->teacher_type)->orderBy('id');
        return $query;
    }
    public function map($report): array
    {
        $this->index++;
        $num_lesson_date = OfflineSchedule::where('course_id', '=', $report->course_id)
            ->where('teacher_main_id', '=', $report->id)
            ->groupBy(['lesson_date'])
            ->count();

        return [
            $this->index,
            $report->code,
            $report->teacher,
            $report->course_code,
            $report->course_name,
            get_date($report->start_date),
            get_date($report->end_date),
            $num_lesson_date,
            $report->lesson,
            $report->training_location,
            number_format($report->cost,2),
            ''
        ];
    }

    public function headings(): array
    {
        return [
            [],
            [],
            [],
            [],
            ['BÁO CÁO GIẢNG VIÊN'],
            ['Từ '.$this->from_date.' - '.$this->to_date],
            [],
            [
            'STT',
            'Mã GV',
            'Họ Tên GV',
            trans('lacourse.course_code'),
            'Khóa học',
            'Thời gian bắt đầu',
            'Thời gian kết thúc',
            'Số ngày đứng lớp',
            'Số giờ đứng lớp',
            'Địa điểm',
            'Chi phí',
            'Ghi chú',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $header = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],

                    ],
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
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
                $event->sheet->getDelegate()
                    ->getStyle("A".($this->startRow()-1).":L".($this->startRow()-1))
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->mergeCells('A5:L5')->getStyle('A5')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A6:L6')->getStyle('A6')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:L'.(8 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],

                        ],
                        'font' => [
                            'name' => 'Arial',
                            'size' =>  12,
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
