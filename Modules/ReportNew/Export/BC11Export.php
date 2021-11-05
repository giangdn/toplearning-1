<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Area;
use App\Models\Categories\Discipline;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\Unit;
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
use Modules\Offline\Entities\OfflineSchedule;
use Modules\ReportNew\Entities\BC11;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC11Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 22;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
    }

    public function query()
    {
        $query = BC11::sql($this->from_date, $this->to_date)->orderBy('user_id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $obj = [];
        $this->index++;
        $unit = Unit::whereCode($row->unit_code_1)->first();
        $area = Area::find(@$unit->area_id);

        $offline = OfflineCourse::find($row->course_id);
        $course_time = $offline->course_time;

        $schedules = OfflineSchedule::query()
            ->where('course_id', '=', $row->course_id)
            ->where(function ($sub) use ($row){
                $sub->where('teacher_main_id', '=', $row->training_teacher_id);
                $sub->orWhere('teach_id', '=', $row->training_teacher_id);
            })
            ->get();
        $time_schedule = '';
        foreach ($schedules as $schedule){
            if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                $time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
            }else{
                $time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
            }
        }

        $obj[] = $this->index;
        $obj[] = $row->user_code;
        $obj[] = $row->fullname;
        $obj[] = $row->role_lecturer == 1 ? 'X' : '';
        $obj[] = $row->role_tuteurs == 1 ? 'X' : '';
        $obj[] = $row->account_number.' ';
        $obj[] = @$area->name;
        // $obj[] = $row->unit_code_1;
        $obj[] = $row->unit_name_1;
        // $obj[] = $row->unit_code_2;
        $obj[] = $row->unit_name_2;
        // $obj[] = $row->unit_code_3;
        // $obj[] = $row->unit_name_3;
        $obj[] = $row->title_name;
        $obj[] = $row->course_code;
        $obj[] = $row->course_name;
        $obj[] = $row->training_form_name;
        $obj[] = $course_time;
        $obj[] = $row->time_lecturer;
        $obj[] = $row->time_tuteurs;
        $obj[] = get_date($row->start_date);
        $obj[] = get_date($row->end_date);
        $obj[] = $time_schedule;
        $obj[] = $row->training_location_name;

//        if ($row->role_lecturer == 1){
//            $obj[] = number_format($row->cost_lecturer, 2);
//            $total = $row->cost_lecturer;
//        }
//        if ($row->role_tuteurs == 1){
//            $obj[] = number_format($row->cost_tuteurs, 2);
//            $total = $row->cost_tuteurs;
//        }
        $total = 0;
        $training_cost = TrainingCost::query()->where('type', '=', 4)->orderBy('id')->get();
        foreach ($training_cost as $item){
            $offline_cost = OfflineCourseCost::whereCourseId($row->course_id)->where('cost_id', '=', $item->id)->first();
            $obj[] = number_format(@$offline_cost->actual_amount, 2);

            $total += @$offline_cost->actual_amount;
        }
        $obj[] = number_format($total, 2);
        $obj[] = $row->role_lecturer == 1 ? $row->teacher : 0;

        return $obj;
    }

    public function headings(): array
    {
        $title_arr = [];
        $title_arr[] = 'STT';
        $title_arr[] = 'Mã nhân viên';
        $title_arr[] = 'Họ và tên';
        $title_arr[] = 'Vai trò giảng viên';
        $title_arr[] = 'Vai trò trợ giảng';
        $title_arr[] = 'Số tài khoản';
        $title_arr[] = 'Khu vực';
        // $title_arr[] = 'Mã đơn vị cấp 1';
        $title_arr[] = 'Đơn vị trực tiếp';
        // $title_arr[] = 'Mã đơn vị cấp 2';
        $title_arr[] = 'Đơn vị quản lý';
        // $title_arr[] = 'Mã đơn vị cấp 3';
        // $title_arr[] = 'Đơn vị cấp 3';
        $title_arr[] = 'Chức danh';
        $title_arr[] = trans('lacourse.course_code');
        $title_arr[] = trans('lacourse.course_name');
        $title_arr[] = 'Hình thức đào tạo';
        $title_arr[] = 'Thời lượng khóa học';
        $title_arr[] = 'Thời lượng dạy chính (giờ)';
        $title_arr[] = 'Thời lượng trợ giảng (giờ)';
        $title_arr[] = 'Từ ngày';
        $title_arr[] = 'Đến ngày';
        $title_arr[] = 'Thời gian';
        $title_arr[] = 'Địa điểm đào tạo';
        /*$title_arr[] = 'Chi phí giảng dạy';*/

        $training_cost = TrainingCost::query()->where('type', '=', 4)->orderBy('id')->get();
        foreach ($training_cost as $item){
            $title_arr[] = $item->name;
            $this->count_title += 1;
        }

        $title_arr[] = 'Tổng chi phí';
        $title_arr[] = 'Kết quả đánh giá (%)';

        return [
            [],
            [],
            [],
            [],
            [],
            ['THỐNG KÊ GIẢNG VIÊN ĐÀO TẠO (NỘI BỘ & BÊN NGOÀI) THEO THÁNG/QUÝ/NĂM'],
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
