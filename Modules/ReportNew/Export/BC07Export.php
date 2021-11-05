<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\Area;
use App\Models\Categories\Position;
use App\Models\Categories\TrainingType;
use App\Profile;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\CourseOld\Entities\CourseOld;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\ReportNew\Entities\BC07;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\Unit;

class BC07Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($param)
    {
        $this->user_id = $param->user_id;
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->unit_id = isset($param->unit_id) ? $param->unit_id : null;
        $this->area_id = $param->area;
    }

    public function query()
    {
        $query = BC07::sql($this->user_id, $this->from_date, $this->to_date, $this->unit_id, $this->area_id)->orderBy('el_training_process.id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $this->index++;

        $profile = Profile::query()->find($row->user_id);
        $position = Position::query()->find($profile->position_id);
        $title = @$profile->titles;
        $unit_1 = @$profile->unit;
        $unit_2 = @$unit_1->parent;
        $area = Area::find(@$unit_1->area_id);

        $row->user_code = $profile->code;
        $row->fullname = $profile->getFullName();
        $row->email = $profile->email;
        $row->phone = $profile->phone;
        if ($row->course_old == 1){
            $courseOld = CourseOld::whereCourseCode($row->course_code)->whereUserCode($profile->code)->where('start_date', $row->start_date)->first();
            $data_course_old = $courseOld ? json_decode($courseOld->data, true) : [];

            $row->area = $courseOld ? $data_course_old['Khu vực'] : '';
            $row->unit_name_1 = $courseOld ? $data_course_old['Đơn vị trực tiếp'] : '';
            $row->unit_name_2 = $courseOld ? $data_course_old['Đơn vị quản lý'] : '';
            $row->position_name = $courseOld ? $data_course_old['Chức vụ'] : '';
            $row->title_name = $courseOld ? $data_course_old['Chức danh'] : '';
            $row->training_unit = $courseOld ? $data_course_old['Đơn vị đào tạo'] : '';
            $row->process_type = $courseOld ? ($data_course_old['Hình thức đào tạo'] == 1 ? 'Đào tạo trực tuyến' : 'Đào tạo tập trung') : '';
            $row->course_time = $courseOld ? $data_course_old['Thời lượng khóa học'] : '';
            $row->attendance = $courseOld ? $data_course_old['Tổng thời lượng tham gia'] : '';
            $row->start_date = $courseOld ? $data_course_old['Từ ngày'] : '';
            $row->end_date = $courseOld ? $data_course_old['Đến ngày'] : '';
            $row->time_schedule = $courseOld ? $data_course_old['Thời gian'] : '';
            $row->course_cost = $courseOld ? number_format($data_course_old['Bình quân CP Học viên']) : '';
            $row->score = $courseOld ? $data_course_old['Điểm'] : '';
            $row->result = $courseOld ? $data_course_old['Kết quả'] : '';
        }else {
            $row->area = @$area->name;
            $row->unit_name_1 = @$unit_1->name;
            $row->unit_name_2 = @$unit_2->name;
            $row->position_name = @$position->name;
            $row->title_name = @$title->name;
            if ($row->course_type == 2) {
                $course = OfflineCourse::query()->find($row->course_id);
                $unit_name = [];
                !empty($course->training_unit) ? $training_unit = json_decode($course->training_unit) : $training_unit = [];
                if($course->training_unit_type == 0 && !empty($training_unit)) {
                    $units = Unit::where('status', '=', 1)->get();
                    foreach ($units as $key => $unit) {
                        if(in_array($unit->id, $training_unit)) {
                            $unit_name[] = $unit->name;
                        }
                    }
                } else if ($course->training_unit_type == 1 && !empty($training_unit)) {
                    $training_partners = TrainingPartner::get();
                    foreach ($training_partners as $key => $training_partner) {
                        if(in_array($training_partner->id, $training_unit)) {
                            $unit_name[] = $training_partner->name;
                        }
                    }
                }
                $row->training_unit = !empty($unit_name) ? implode(',',$unit_name) : '';

                $row->course_time = @$course->course_time;
                $row->process_type = 'Đào tạo tập trung';

                $register = OfflineRegister::whereCourseId($row->course_id)->where('user_id', '=', $row->user_id)->first();
                $schedules = OfflineSchedule::query()
                    ->select(['a.end_time', 'a.lesson_date'])
                    ->from('el_offline_schedule as a')
                    ->where('a.course_id', '=', $row->course_id)
                    ->get();
                if ($schedules->count() > 0) {
                    foreach ($schedules as $schedule) {
                        if ($schedule->end_time <= '12:00:00') {
                            $row->time_schedule .= 'Sáng ' . get_date($schedule->lesson_date) . '; ';
                        } else {
                            $row->time_schedule .= 'Chiều ' . get_date($schedule->lesson_date) . '; ';
                        }
                    }
                }

                $row->attendance = OfflineAttendance::query()->where('register_id', '=', @$register->id)->count();
                $indemnify = Indemnify::whereCourseId($row->course_id)->whereUserId($row->user_id)->first();
                $student_cost = OfflineStudentCost::getTotalStudentCost($register->id);
                $course_cost = ($indemnify ? $indemnify->commit_amount : 0) + $student_cost;
                $row->course_cost = number_format($course_cost, 2);
            } else {
                $course = OnlineCourse::query()->find($row->course_id);
                $row->course_cost = '';
                $row->course_time = preg_replace("/[^0-9]/", '', @$course->course_time);
                $row->process_type = 'Đào tạo Online';
            }
            $row->start_date = get_date($course->start_date);
            $row->end_date = get_date($course->end_date);
            $row->result = $row->pass == 1 ? 'Đạt' : 'Không đạt';
            $row->score = $row->mark;
        }

        return [
            $this->index,
            $row->course_code,
            $row->course_name,
            $row->user_code,
            $row->fullname,
            $row->email,
            $row->phone,
            $row->area,
            $row->unit_name_1,
            $row->unit_name_2,
            $row->position_name,
            $row->title_name,
            $row->training_unit,
            $row->process_type,
            $row->course_time,
            $row->attendance,
            $row->start_date,
            $row->end_date,
            $row->time_schedule,
            $row->course_cost,
            $row->score,
            $row->result,
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
            ['BÁO CÁO QUÁ TRÌNH ĐÀO TẠO CỦA NHÂN VIÊN'],
            [],
            [
                'STT',
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                'Mã nhân viên',
                'Họ và tên',
                'Email',
                'Điện thoại',
                'Khu vực',
                // 'Mã đơn vị cấp 1',
                'Đơn vị trực tiếp',
                // 'Mã đơn vị cấp 2',
                'Đơn vị quản lý',
                // 'Mã đơn vị cấp 3',
                // 'Đơn vị cấp 3',
                'Chức vụ',
                'Chức danh',
                'Đơn vị đào tạo',
                'Hình thức đào tạo',
                'Thời lượng khóa học',
                'Tổng thời lượng tham gia',
                'Từ ngày',
                'Đến ngày',
                'Thời gian',
                'Chi phí',
                'Điểm',
                'Kết quả',
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

                $event->sheet->getDelegate()->mergeCells('A6:W6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:W8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:W'.(8 + $this->index))
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
