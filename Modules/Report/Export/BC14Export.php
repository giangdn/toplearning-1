<?php
namespace Modules\Report\Export;

use App\Config;
use App\PlanApp;
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
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Rating\Entities\RatingCourse;
use Modules\Report\Entities\BC14;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\Theme;
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

class BC14Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    public $from_date;
    public $to_date;
    public $course_type;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->course_type = $param->type;
    }

    public function query()
    {
        $query = BC14::sql($this->course_type, $this->from_date, $this->to_date)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function map($report): array
    {
        if ($report->course_type == 1){
            $onl_quantily_student = OnlineRegister::where('course_id', '=', $report->id)
                ->where('status', '=', 1)
                ->count('user_id');

            if ($onl_quantily_student == 0){
                $onl_result_achieved = 0;
                $onl_result_not_achieved = 0;
            }else{
                $onl_result = OnlineResult::where('course_id', '=', $report->id)
                    ->where('result', '=', 1)
                    ->count('user_id');

                $onl_not_result = $onl_quantily_student - $onl_result;

                $onl_result_achieved = number_format(($onl_result / $onl_quantily_student) * 100, 0);
                $onl_result_not_achieved = number_format(($onl_not_result / $onl_quantily_student) * 100, 0);
            }

            $onl_cost = OnlineCourseCost::where('course_id', '=', $report->id)->sum('actual_amount');

        }else{
            $register_id = OfflineRegister::where('course_id', '=', $report->id)->pluck('id')->toArray();
            $student_cost = OfflineStudentCost::whereIn('register_id', $register_id)->sum('cost');
            $course_cost = OfflineCourseCost::where('course_id', '=', $report->id)->sum('actual_amount');
            $off_cost = $course_cost + $student_cost;

            $off_quantily_student = OfflineRegister::where('course_id', '=', $report->id)
                ->where('status', '=', 1)
                ->count('user_id');

            if ($off_quantily_student == 0){
                $off_result_achieved = 0;
                $off_result_not_achieved = 0;
            }else {
                $off_result = OfflineResult::where('course_id', '=', $report->id)
                    ->where('result', '=', 1)
                    ->count('user_id');

                $off_not_result = $off_quantily_student - $off_result;

                $off_result_achieved = number_format(($off_result / $off_quantily_student) * 100, 0);
                $off_result_not_achieved = number_format(($off_not_result / $off_quantily_student) * 100, 0);
            }

            $teachers = $this->getTeacher($report->id);
        }

        $this->index++;
        return [
            $this->index,
            $report->code,
            $report->name,
            $report->course_type == 1 ? 'Offline' : 'Tập trung',
            $report->course_type == 1 ? '' : $report->training_unit,
            $report->course_type == 1 ? '' : $report->training_location_name,
            $report->course_type == 1 ? '' : implode(', ', $teachers),
            $report->course_type == 1 ? if_empty($onl_cost, 0) : if_empty($off_cost, 0),
            get_date($report->start_date, 'd/m/Y'),
            get_date($report->end_date, 'd/m/Y'),
            $report->course_type == 1 ? $onl_quantily_student : $off_quantily_student,
            $report->course_type == 1 ? $onl_result_achieved : $off_result_achieved,
            $report->course_type == 1 ? $onl_result_not_achieved : $off_result_not_achieved,
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
            ['Thống kê kết quả đào tạo'],
            ['Từ:' . $this->from_date .' - '. $this->to_date],
            ['STT', trans('lacourse.course_code'), 'Khóa học', 'Hình thức', 'Đơn vị đào tạo', 'Địa điểm', 'Giảng viên', 'Chi phí', 'Thời gian', '', 'Số lượng học viên', 'Kết quả (%)', ''],
            ['', '', '', '', '', '', '', '', 'Từ ngày', 'Đến ngày', '', 'Đạt', 'Không đạt']
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'bold'      =>  true,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()->mergeCells('A7:M7')->getStyle('A7')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A8:M8')->getStyle('A8')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A9:M10')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->mergeCells('A9:A10');
                $event->sheet->getDelegate()->mergeCells('B9:B10');
                $event->sheet->getDelegate()->mergeCells('C9:C10');
                $event->sheet->getDelegate()->mergeCells('D9:D10');
                $event->sheet->getDelegate()->mergeCells('E9:E10');
                $event->sheet->getDelegate()->mergeCells('F9:F10');
                $event->sheet->getDelegate()->mergeCells('G9:G10');
                $event->sheet->getDelegate()->mergeCells('H9:H10');
                $event->sheet->getDelegate()->mergeCells('I9:J9');
                $event->sheet->getDelegate()->mergeCells('K9:K10');
                $event->sheet->getDelegate()->mergeCells('L9:M9');

                $event->sheet->getDelegate()->getStyle('A7:M'.(10 + $this->count).'')->applyFromArray([
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
        return 11;
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

    public function getTeacher($course_id){
        $teacher = OfflineSchedule::leftJoin('el_training_teacher AS b', 'b.id', '=', 'teacher_main_id')
            ->where('course_id', '=', $course_id)
            ->where('b.status', '=', 1)
            ->pluck('b.name')
            ->toArray();

        return $teacher;
    }
}
