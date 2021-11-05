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
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC05;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\Unit;

class BC05Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param)
    {
        $this->course_type = $param->course_type;
        $this->subject_id = $param->subject_id;
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->training_type_id = $param->training_type_id;
        $this->title_id = $param->title_id;
        $this->unit_id = isset($param->unit_id) ? $param->unit_id : null;
        $this->area_id = $param->area;
    }

    public function query()
    {
        $query = BC05::sql($this->course_type, $this->subject_id, $this->from_date, $this->to_date, $this->training_type_id, $this->title_id, $this->unit_id, $this->area_id)->orderBy('el_report_new_export_bc05.id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;
        $status_user = '';
        switch ($row->status_user) {
            case 0:
                $status_user = trans('backend.inactivity'); break;
            case 1:
                $status_user = trans('backend.doing'); break;
            case 2:
                $status_user = trans('backend.probationary'); break;
            case 3:
                $status_user = trans('backend.pause'); break;
        }

        $time_schedule = '';
        if ($row->course_type == 2){
            $offline = OfflineCourse::find($row->course_id);
            $row->course_time = $offline->course_time;
            $unit_name = [];
            !empty($offline->training_unit) ? $training_unit = json_decode($offline->training_unit) : $training_unit = [];
            if($offline->training_unit_type == 0 && !empty($training_unit)) {
                $units = Unit::where('status', '=', 1)->get();
                foreach ($units as $key => $unit) {
                    if(in_array($unit->id, $training_unit)) {
                        $unit_name[] = $unit->name;
                    }
                }
            } else if ($offline->training_unit_type == 1 && !empty($training_unit)) {
                $training_partners = TrainingPartner::get();
                foreach ($training_partners as $key => $training_partner) {
                    if(in_array($training_partner->id, $training_unit)) {
                        $unit_name[] = $training_partner->name;
                    }
                }
            }
            $row->training_unit = !empty($unit_name) ? implode(',',$unit_name) : '';

            $register = OfflineRegister::whereCourseId($row->course_id)->where('user_id', '=', $row->user_id)->first();
            $schedules = OfflineSchedule::query()
                ->select(['a.end_time', 'a.lesson_date'])
                ->from('el_offline_schedule as a')
                ->where('a.course_id', '=', $row->course_id)
                ->get();
            foreach ($schedules as $schedule){
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                    $time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }
            }
        }

        return [
            $this->index,
            $row->course_code,
            $row->course_name,
            $row->user_code,
            $row->fullname,
            $row->email,
            $row->area_name_unit,
            $row->phone,
            // $row->area,
            // $row->unit_code_1,
            $row->unit_name_1,
            // $row->unit_code_2,
            $row->unit_name_2,
            // $row->unit_code_3,
            // $row->unit_name_3,
            $row->unit_type_name,
            $row->position_name,
            $row->title_name,
            $row->training_unit,
            $row->training_type_name,
            $row->course_time,
            $row->attendance,
            get_date($row->start_date),
            get_date($row->end_date),
            $time_schedule,
            $row->score,
            $row->result == 1 ? 'Đạt' : 'Không đạt',
            $status_user,
            $row->note,
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
            ['BÁO CÁO DANH SÁCH HỌC VIÊN THAM GIA KHÓA HỌC'],
            [],
            [
                'STT',
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                'Mã nhân viên',
                'Họ và tên',
                'Email',
                'Khu vực',
                'Điện thoại',
                // 'Vùng',
                // 'Mã đơn vị cấp 1',
                'Đơn vị trực tiếp',
                // 'Mã đơn vị cấp 2',
                'Đơn vị quản lý',
                // 'Mã đơn vị cấp 3',
                // 'Đơn vị cấp 3',
                'Loại đơn vị',
                'Chức vụ',
                'Chức danh',
                'Đơn vị đào tạo',
                'Loại hình đào tạo',
                'Thời lượng khóa học',
                'Tổng thời lượng tham gia',
                'Từ ngày',
                'Đến ngày',
                'Thời gian',
                'Điểm',
                'Kết quả',
                'Trạng thái',
                'Ghi chú',
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

                $event->sheet->getDelegate()->mergeCells('A6:X6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:X8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:X'.(8 + $this->index))
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
