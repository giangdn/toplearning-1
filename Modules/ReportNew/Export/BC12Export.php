<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Area;
use App\Models\Categories\StudentCost;
use App\Models\Categories\UnitType;
use App\ProfileView;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineSchedule;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\Unit;

use Modules\Offline\Entities\OfflineStudentCost;
use Modules\ReportNew\Entities\BC12;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC12Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 28;

    public function __construct($param)
    {
        $this->training_area_id = $param->training_area_id;
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->training_type_id = $param->training_type_id;
        $this->title_id = $param->title_id;
        $this->unit_id = isset($param->unit_id) ? $param->unit_id : null;
    }

    public function query()
    {
        $query = BC12::sql($this->training_area_id, $this->from_date, $this->to_date, $this->training_type_id, $this->title_id, $this->unit_id)->orderBy('course_id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $profile = ProfileView::whereUserId(@$row->user_id)->first();
        $unit = Unit::find($profile->unit_id);
        $area = Area::find(@$unit->area_id);
        $unit_type = UnitType::find(@$unit->type);
        $offline = OfflineCourse::find($row->course_id);
        $offline_result = OfflineResult::whereCourseId($row->course_id)->whereUserId($row->user_id)->first();

        $obj = [];
        $this->index++;

        $row->user_code = $profile->code;
        $row->fullname = $profile->full_name;
        $row->email = $profile->email;
        $row->area_name_unit = @$area->name;
        $row->phone = $profile->phone;
        $row->unit_name_1 = $profile->unit_name;
        $row->unit_name_2 = $profile->parent_unit_name;
        $row->unit_type_name = @$unit_type->anme;
        $row->position_name = $profile->position_name;
        $row->title_name = $profile->title_name;

        $status_user = '';
        switch (@$profile->status_id) {
            case 0:
                $status_user = trans('backend.inactivity'); break;
            case 1:
                $status_user = trans('backend.doing'); break;
            case 2:
                $status_user = trans('backend.probationary'); break;
            case 3:
                $status_user = trans('backend.pause'); break;
        }

        $row->course_time = $offline->course_time;
        $row->course_code = $offline->code;
        $row->course_name = $offline->name;
        $row->start_date = get_date($offline->start_date);
        $row->end_date = get_date($offline->end_date);

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

        $time_schedule = '';
        $note = '';
        foreach ($schedules as $key => $schedule){
            if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                $time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
            }else{
                $time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
            }

            if ($schedule->absent_reason_id != 0){
                $absent_reason = AbsentReason::find($schedule->absent_reason_id);
                $note .= $absent_reason ? 'Buổi '.($key + 1).': '.$absent_reason->name.'; ' : '';
            }
        }

        $obj[] = $this->index;
        $obj[] = $row->user_code;
        $obj[] = $row->fullname;
        $obj[] = $row->email;
        $obj[] = $row->area_name_unit;
        $obj[] = $row->phone;
        $obj[] = $row->unit_name_1;
        $obj[] = $row->unit_name_2;
        $obj[] = $row->unit_type_name;
        $obj[] = $row->position_name;
        $obj[] = $row->title_name;
        $obj[] = $row->course_code;
        $obj[] = $row->course_name;
        $obj[] = $row->training_unit;
        $obj[] = 'Đào tạo tập trung';
        $obj[] = $row->course_time;
        $obj[] = get_date($row->start_date);
        $obj[] = get_date($row->end_date);
        $obj[] = $time_schedule;
        $obj[] = $row->attendance;
        $obj[] = $status_user;
        $obj[] = $offline_result ? $offline_result->score : '_';
        $obj[] = $offline_result ? $offline_result->result == 1 ? 'Đạt' : 'Không đạt' : '_';

        $total_cost = 0;
        $student_cost = StudentCost::whereStatus(1)->get();
        foreach ($student_cost as $item){
            $offline_student_cost = OfflineStudentCost::whereRegisterId($register->id)->where('cost_id', '=', $item->id)->first();
            $obj[] = number_format(@$offline_student_cost->cost, 2);

            $total_cost += @$offline_student_cost->cost;
        }

        $off_register = OfflineRegister::whereCourseId($row->course_id)->where('status', '=', 1)->count();
        $total_register = $off_register > 0 ? $off_register : 1;

        $teacher_cost = OfflineCourseCost::sumActualAmount($row->course_id, 4);
        $organizational_costs = OfflineCourseCost::sumActualAmount($row->course_id, 1);
        $academy_cost = OfflineCourseCost::sumActualAmount($row->course_id, 5);

        $avg_teacher_cost = ($teacher_cost/$total_register);
        $avg_organizational_costs = ($organizational_costs/$total_register);
        $avg_academy_cost = ($academy_cost/$total_register);

        $total_cost += ($avg_teacher_cost + $avg_organizational_costs + $avg_academy_cost);

        $obj[] = number_format($avg_teacher_cost, 2);
        $obj[] = number_format($avg_organizational_costs, 2);
        $obj[] = number_format($avg_academy_cost, 2);
        $obj[] = number_format($total_cost, 2);
        $obj[] = $note;

        return $obj;
    }

    public function headings(): array
    {
        $title_arr = [];

        $title_arr[] = 'STT';
        $title_arr[] = 'Mã nhân viên';
        $title_arr[] = 'Họ và tên';
        $title_arr[] = 'Email';
        $title_arr[] = 'Khu vực';
        $title_arr[] = 'Điện thoại';
        // $title_arr[] = 'Vùng';
        // $title_arr[] = 'Mã đơn vị cấp 1';
        $title_arr[] = 'Đơn vị trực tiếp';
        // $title_arr[] = 'Mã đơn vị cấp 2';
        $title_arr[] = 'Đơn vị quản lý';
        // $title_arr[] = 'Mã đơn vị cấp 3';
        // $title_arr[] = 'Đơn vị cấp 3';
        $title_arr[] = 'Loại đơn vị';
        $title_arr[] = 'Chức vụ';
        $title_arr[] = 'Chức danh';
        $title_arr[] = trans('lacourse.course_code');
        $title_arr[] = trans('lacourse.course_name');
        $title_arr[] = 'Đơn vị đào tạo';
        $title_arr[] = 'Hình thức đào tạo';
        $title_arr[] = 'Thời lượng khóa học';
        $title_arr[] = 'Từ ngày';
        $title_arr[] = 'Đến ngày';
        $title_arr[] = 'Thời gian';
        $title_arr[] = 'Tổng thời lượng tham gia';
        $title_arr[] = 'Trạng thái';
        $title_arr[] = 'Điểm';
        $title_arr[] = 'Kết quả';

        $student_cost = StudentCost::whereStatus(1)->get();
        foreach ($student_cost as $item){
            $title_arr[] = $item->name;
            $this->count_title += 1;
        }

        $title_arr[] = 'Bình quân CPGV';
        $title_arr[] = 'Bình quân CPTC';
        $title_arr[] = 'Bình quân CP Học viện';
        $title_arr[] = 'Tổng CP';
        $title_arr[] = 'Ghi chú';

        return [
            [],
            [],
            [],
            [],
            [],
            ['THỐNG KÊ CHI TIẾT HỌC VIÊN THEO ĐƠN VỊ'],
            [],
            $title_arr
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

                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                if ($this->count_title > 26){
                    $num = floor($this->count_title/26);
                    $num_1 = $this->count_title - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($this->count_title - 1)];
                }

                $event->sheet->getDelegate()->mergeCells('A6:'.$char.'6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:'.$char.'8')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:'.$char.(8 + $this->index))
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
