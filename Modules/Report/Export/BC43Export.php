<?php
namespace Modules\Report\Export;

use App\Config;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\Unit;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineRegister;
use Modules\Report\Entities\BC43;
use Modules\TrainingPlan\Entities\TrainingPlan;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC43Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;

    public function __construct($param)
    {
        $this->course_id = $param->course;
        $this->course_type = $param->course_type;
//        $this->from_date = $param->from_date;
//        $this->to_date = $param->to_date;
    }

    public function query()
    {
        $query = BC43::sql($this->course_id,$this->course_type)->orderBy('id', 'ASC');
        return $query;
    }

    public function map($report): array
    {
        $this->index++;

        return [
            $this->index,
            $report->course_name,
            get_date($report->start_date),
            get_date($report->end_date),
            $report->training_unit,
            $report->code,
            $report->lastname.' '.$report->firstname,
            $report->title,
            '',
            $report->reality_manager==1?'X':'',
            $report->reality_manager==2?$report->reason_reality_manager:'',
            $report->result==1?'Đạt':'Không đạt'
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
            [],
            ['BÁO CÁO HIỆU QUẢ SAU ĐÀO TẠO'],
            [' '],
            [
                'STT',
                trans('lacourse.course_name'),
                'Ngày bắt đầu',
                'Ngày kết thúc',
                'Đơn vị đào tạo',
                'MSNV',
                'Họ và tên',
                'Chức danh',
                'Mục tiêu đào tạo',
                'Vận dụng được vào thực tế công việc',
                'Chưa vận dụng được vào thực tế công việc (lý do)',
                'Kết luận'
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size' =>  12,
                        'bold' =>  true,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->mergeCells('A7:L7')->getStyle('A7')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A9:L9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A9:L'.(9 + $this->index))->applyFromArray([
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
        return 10;
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
