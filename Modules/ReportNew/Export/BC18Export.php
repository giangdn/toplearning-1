<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\Area;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC18;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC18Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $column = 1;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->area_id = $param->area_id;
        $this->title_id = $param->title_id;
        $this->unit_id = $param->unit_id;
        $this->training_type_id = $param->training_type_id;
    }

    public function query()
    {
        $query = BC18::sql($this->title_id, $this->unit_id,$this->area_id,$this->training_type_id, $this->from_date, $this->to_date)->orderBy('full_name', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $offline = OfflineCourse::find($row->course_id);
        $row->course_time = @$offline->course_time;
        $schedules = OfflineSchedule::query()
            ->select(['a.end_time', 'a.lesson_date'])
            ->from('el_offline_schedule as a')
            ->where('a.course_id', '=', $row->course_id)
            ->get();

        foreach ($schedules as $schedule){
            if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                $row->time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
            }else{
                $row->time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
            }
        }

        $obj = [];
        $this->index++;
        $obj[] = $this->index;
        $obj[]=$row->user_code;
        $obj[]=$row->full_name;
        $obj[]=$row->email;
        $obj[]=$row->phone;
        // $obj[]=$row->area;
        // $obj[]=$row->unit1_code;
        $obj[]=$row->unit1_name;
        // $obj[]=$row->unit2_code;
        $obj[]=$row->unit2_name;
        // $obj[]=$row->unit3_code;
        // $obj[]=$row->unit3_name;
        $obj[]=$row->position_name;
        $obj[]=$row->titles_name;
        $obj[]=$row->training_program_name;
        $obj[]=$row->subject_name;
        $obj[]=$row->course_code;
        $obj[]=$row->course_name;
        $obj[]=$row->training_unit;
        $obj[]=$row->training_type;
        $obj[]=$row->training_address;
        $obj[]=$row->course_time;
        $obj[]=get_date($row->start_date);
        $obj[]=get_date($row->end_date);
        $obj[]=$row->time_schedule;
        $obj[]= number_format($row->cost_held, 2);
        $obj[]= number_format($row->cost_training, 2);
        $obj[]= number_format($row->cost_external, 2);
        $obj[]= number_format($row->cost_teacher, 2);
        $obj[]= number_format($row->cost_student, 2);
        $obj[]= number_format($row->cost_total, 2);
        $obj[]=$row->time_commit;
        $obj[]=get_date($row->from_time_commit).' - '.get_date($row->to_time_commit);
        $obj[]=$row->time_rest;
        $obj[]= number_format($row->cost_refund, 2);
        return $obj;
    }

    public function headings(): array
    {
        $title = Titles::find($this->title_id);
        $area = Area::find($this->area_id);
        $unit = Unit::find($this->unit_id);
        $trainingType = TrainingType::find($this->training_type_id);
        $colHeader= [
            'STT',
            'Mã nhân viên',
            'Họ và tên',
            'Email',
            'Điện thoại',
            // 'Khu vực',
            // 'Mã đơn vị cấp 1',
            'Đơn vị trực tiếp',
            // 'Mã đơn vị cấp 2',
            'Đơn vị quản lý',
            // 'Mã đơn vị cấp 3',
            // 'Đơn vị cấp 3',
            'Chúc vụ',
            'Chức danh',
            'Tên chương trình',
            'Tên chuyên đề',
            trans('lacourse.course_code'),
            trans('lacourse.course_name'),
            'Đơn vị đào tạo',
            'Hình thức đào tạo',
            'Địa điểm đào tạo',
            'Thời lượng khóa',
            'Từ ngày',
            'Đến ngày',
            'Thời gian',
            'Bình quân chi phí tổ chức',
            'Bình quân chi phí phòng đào tạo',
            'Bình quân chi phí bên ngoài',
            'Bình quân chi phí giảng viên',
            'Bình quân chi phí học viên',
            'Tổng chi phí',
            'Số ngày cam kết',
            'Thời gian cam kết',
            'Thời hạn còn',
            'Chi phí bồi hoàn (đồng)'
        ];
        return [
            [],
            [],
            [],
            [],
            [],
            ['DANH SÁCH XÁC NHẬN BỒI HOÀN CHI PHÍ ĐÀO TẠO ĐỐI VỚI CBNV TÂN TUYỂN'],
            ['Thời gian : '. (($this->from_date && $this->to_date) ? get_date($this->from_date).' - '.get_date($this->to_date):'')],
            ['Chức danh : '.@$title->name],
            ['Vùng : '.@$area->name],
            ['Đơn vị : '.@$unit->name],
            ['Loại hình đào tạo : '.@$trainingType->name],
            [],
            $colHeader
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

                $event->sheet->getDelegate()->mergeCells('A6:Z6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A13:AD13')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A13:AD'.(13 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ])->getAlignment()->setWrapText(true);
            },

        ];
    }
    public function startRow(): int
    {
        return 12;
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
