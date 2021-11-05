<?php
namespace Modules\Report\Export;
use App\Config;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Rating\Entities\RatingCourse;
use Modules\Report\Entities\BC12;
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

class BC12Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    private $course;
    private $course_type;
    protected $count = 0;
    public function __construct($param)
    {
        $this->course = $param->course;
        $this->course_type = $param->type;
    }

    public function query()
    {
        $query = BC12::sql($this->course, $this->course_type)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($report): array
    {
        $this->index++;
        $profile = Profile::find($report->user_id);
        $arr_unit = Unit::getTreeParentUnit($profile->unit_code);

        return [
            $this->index,
            $report->code,
            $report->lastname . ' ' . $report->firstname,
            $report->title_name,
            $arr_unit ? $arr_unit[$profile->unit->level]->name : '',
            $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '',
            $arr_unit ? $arr_unit[2]->name : '',
            $report->gender == 1 ? 'Nam' : 'Nữ',
            $report->email,
            $report->note,
        ];
    }
    public function headings(): array
    {
        if ($this->course_type == 1){
            $course = OnlineCourse::find($this->course);
            $cost_onl = OnlineCourseCost::where('course_id', '=', $this->course)->sum('actual_amount');
        }else{
            $course = OfflineCourse::find($this->course);
            $training_location = TrainingLocation::find($course->training_location_id);

            $cost_course = OfflineCourseCost::where('course_id', '=', $this->course)->sum('actual_amount');
            $register_id = OfflineRegister::where('course_id', '=', $this->course)->pluck('id')->toArray();
            $cost_student = OfflineStudentCost::whereIn('register_id', $register_id)->sum('cost');

            $cost_off = $cost_course + $cost_student;
        }

        $title = [];
        $title[] = 'STT';
        $title[] = 'Mã nhân viên';
        $title[] = 'Họ tên';
        $title[] = 'Chức danh';
        $title[] = 'Đơn vị trực tiếp';
        $title[] = 'Đơn vị gián tiếp cấp 1';
        $title[] = 'Công ty';
        $title[] = 'Giới tính';
        $title[] = 'Email';
        $title[] = 'Ghi chú';

        return [
            [],
            [],
            [],
            [],
            [],
            [],
            ['Thống kê đăng ký khóa học'],
            [trans('lacourse.course_code'),'', $course->code],
            [trans('lacourse.course_name'),'', $course->name],
            ['Hình thức','', $this->course_type == 1 ? 'Offline' : 'Tập trung'],
            ['Ngày bắt đầu','', get_date($course->start_date, 'd/m/Y')],
            ['Ngày kết thúc','', get_date($course->end_date, 'd/m/Y')],
            ['Địa điểm','', $this->course_type == 1 ? '' : ($training_location ? $training_location->name : '')],
            ['Chi phí','', $this->course_type == 1 ? $cost_onl : $cost_off . ' VND'],
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
                $event->sheet->getDelegate()->mergeCells('A7:F7')->getStyle('A7')->applyFromArray($title);

                $event->sheet->getDelegate()
                    ->getStyle('A8:B14')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->mergeCells('C8:F8');
                $event->sheet->getDelegate()->mergeCells('C9:F9');
                $event->sheet->getDelegate()->mergeCells('C10:F10');
                $event->sheet->getDelegate()->mergeCells('C11:F11');
                $event->sheet->getDelegate()->mergeCells('C12:F12');
                $event->sheet->getDelegate()->mergeCells('C13:F13');
                $event->sheet->getDelegate()->mergeCells('C14:F14');

                $event->sheet->getDelegate()->mergeCells('A8:B8');
                $event->sheet->getDelegate()->mergeCells('A9:B9');
                $event->sheet->getDelegate()->mergeCells('A10:B10');
                $event->sheet->getDelegate()->mergeCells('A11:B11');
                $event->sheet->getDelegate()->mergeCells('A12:B12');
                $event->sheet->getDelegate()->mergeCells('A13:B13');
                $event->sheet->getDelegate()->mergeCells('A14:B14');

                $event->sheet->getDelegate()
                    ->getStyle('A16:J16')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A16:J'.(16 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A7:F14')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },

        ];
    }
    public function startRow(): int
    {
        return 17;
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
