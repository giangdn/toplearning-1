<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Discipline;
use App\ProfileView;
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
use Modules\ReportNew\Entities\BC10;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC10Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param)
    {
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
        $query = BC10::sql($this->subject_id, $this->from_date, $this->to_date, $this->training_type_id, $this->title_id, $this->unit_id, $this->area_id)->orderBy('id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;
        $profile = ProfileView::whereUserId($row->user_id)->first();
        $course = OfflineCourse::find($row->course_id);

        $row->user_code = $profile->code;
        $row->full_name = $profile->full_name;
        $row->email = $profile->email;
        $row->phone = $profile->phone;
        $row->unit_name_1 = $profile->unit_name;
        $row->unit_name_2 = $profile->parent_unit_name;
        $row->position_name = $profile->position_name;
        $row->course_code = $course->code;
        $row->course_name = $course->name;
        $row->course_time = $course->course_time;
        $row->start_date = get_date($course->start_date);
        $row->end_date = get_date($course->end_date);

        $status_user = '';
        switch ($profile->status_id) {
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
        $schedule_discipline = '';
        $discipline_name = '';
        $absent_name = '';
        $absent_reason_name = '';

        $schedules = OfflineSchedule::query()
            ->select([
                'a.end_time',
                'a.lesson_date',
                'b.absent_id',
                'b.absent_reason_id',
                'b.discipline_id',
            ])
            ->from('el_offline_schedule as a')
            ->leftJoin('el_offline_attendance as b', 'b.schedule_id', '=', 'a.id')
            ->where('a.course_id', '=', $row->course_id)
            ->where('b.register_id', '=', $row->id)
            ->get();
        foreach ($schedules as $schedule){
            if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                $time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
            }else{
                $time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
            }

            if ($schedule->absent_id != 0 || $schedule->absent_reason_id != 0 || $schedule->discipline_id != 0){
                if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                    $schedule_discipline .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                }else{
                    $schedule_discipline .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                }

                $discipline = Discipline::find($schedule->discipline_id);
                $absent = Absent::find($schedule->absent_id);
                $absent_reason = AbsentReason::find($schedule->absent_reason_id);

                $discipline_name = $discipline ? $discipline->name.'; ' : '';
                $absent_name = $absent ? $absent->name.'; ' : '';
                $absent_reason_name = $absent_reason ? $absent_reason->name.'; ' : '';
            }
        }

        $row->attendance = $schedules->count();

        return [
            $this->index,
            $row->user_code,
            $row->fullname,
            $row->email,
            $row->area_name_unit,
            $row->phone,
            $row->unit_name_1,
            $row->unit_name_2,
            $row->unit_type_name,
            $row->position_name,
            $row->title_name,
            $row->course_code,
            $row->course_name,
            $row->course_time,
            $row->start_date,
            $row->end_date,
            $time_schedule,
            $row->attendance,
            $schedule_discipline,
            $discipline_name,
            $absent_name,
            $absent_reason_name,
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
            ['DANH SÁCH CBNV KHÔNG CHẤP HÀNH NỘI QUY ĐÀO TẠO'],
            [],
            [
                'STT',
                'Mã nhân viên',
                'Họ và tên',
                'Email',
                'Khu vực',
                'Điện thoại',
                'Đơn vị trực tiếp',
                'Đơn vị quản lý',
                'Loại đơn vị',
                'Chức vụ',
                'Chức danh',
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                'Thời lượng khóa học',
                'Từ ngày',
                'Đến ngày',
                'Thời gian',
                'Tổng thời lượng tham gia',
                'Buổi học vi phạm',
                'Vi phạm',
                'Loại nghỉ',
                'Lý do vắng',
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
