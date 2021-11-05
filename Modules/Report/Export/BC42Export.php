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
use Modules\Report\Entities\BC42;
use Modules\TrainingPlan\Entities\TrainingPlan;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC42Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;

    public function __construct($param)
    {
        $this->course_type = $param->course_type;
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
    }

    public function query()
    {
        $query = BC42::sql($this->course_type, $this->from_date, $this->to_date)->orderBy('id', 'ASC');
        return $query;
    }

    public function map($report): array
    {
        // dd($report->start_date);
        $this->index++;
        if ($report->start_date > now()){
            $progress = 'Chưa tới thời gian đào tạo';
        }

        if ($report->end_date){
            if ($report->end_date < now()){
                $progress = 'Đã kết thúc đào tạo';
            }else{
                $progress = 'Đang đào tạo';
            }
        }else{
            $progress = 'Đang đào tạo';
        }

        if (get_date($report->start_date, 'd') >= 1 && get_date($report->start_date, 'm') >= 1){
            $time = get_date($report->start_date, 'Y') . ' -> ' . (get_date($report->start_date, 'Y') + 1);
        }

        if ($report->course_type == 1){
            $course = OnlineCourse::find($report->id);
            $register = OnlineRegister::where('course_id', '=', $course->id)->where('status', '=', 1)->count();
            $course_cost = OnlineCourseCost::getTotalActualAmount($course->id);
            $training_unit = '';
            $training_form = '';
            $training_location = '';
            $schedule = '';
        }else{
            $course = OfflineCourse::find($report->id);
            $training_form = TrainingForm::find($course->training_form_id);
            $training_unit = $course->training_unit;
            $training_location = TrainingLocation::find($course->training_location_id);
            $schedule = OfflineSchedule::where('course_id', '=', $course->id)->count();
            $register = OfflineRegister::where('course_id', '=', $course->id)->where('status', '=', 1)->count();
            $course_cost = OfflineCourseCost::sumActualAmount($course->id);
        }
        $training_plan = TrainingPlan::find($course->in_plan);
        $level_subject = LevelSubject::find($course->level_subject_id);

        $unit = '';
        if ($course->unit_id){
            $arr_unit = explode(',', $course->unit_id);
            $unit = Unit::whereIn('id', $arr_unit)->pluck('name')->toArray();
        }

        return [
            $this->index,
            $time,
            $training_plan ? 'Trong kế hoạch' : 'Ngoài kế hoạch',
            get_date($report->start_date, 'm'),
            $progress,
            $unit ? implode('; ', $unit) : '',
            $report->name,
            $level_subject ? $level_subject->name : '',
            $training_form ? $training_form->name : '',
            $training_plan ? $training_plan->name : '',
            $course->getObject(),
            $training_unit,
            $training_location ? $training_location->name : '',
            get_date($report->start_date),
            get_date($report->end_date),
            '',
            $schedule ? $schedule : '',
            $register,
            $course_cost,
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
            [],
            [],
            ['BÁO CÁO ĐÀO TẠO'],
            [' '],
            [
                'STT',
                'NĐTC',
                'Thực tế tổ chức / Kế hoạch NĐTC',
                'Tháng',
                'Tiến độ',
                'Đơn vị',
                trans('lacourse.course_name'),
                'Cấp độ',
                'Loại hình đào tạo',
                'Trong kế hoạch',
                'Đối tượng',
                'Đơn vị đào tạo',
                'Địa điểm đào tạo',
                'Ngày bắt đầu',
                'Ngày kết thúc',
                'Số lớp',
                'Số buổi đào tạo',
                'Số lượt học viên',
                'Kinh phí (Đồng)',
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
                        'size' =>  12,
                        'bold' =>  true,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->mergeCells('A7:T7')->getStyle('A7')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A9:T9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A9:T'.(9 + $this->index))->applyFromArray([
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
