<?php
namespace Modules\Report\Export;

use App\Config;
use App\PlanApp;
use App\Models\Categories\TrainingForm;
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
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Rating\Entities\RatingCourse;
use Modules\Report\Entities\BC15;
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

class BC15Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->unit_id = $param->unit_id;
    }

    public function query()
    {
        $query = BC15::sql($this->unit_id, $this->from_date, $this->to_date)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($report): array
    {
        $scorm = [];
        if ($report->course_type == 1){
            $onl_result = OnlineResult::where('course_id', '=', $report->id)->where('user_id', '=', $report->user_id)
                ->where('result', '=', 1)->first();

            $onl_cost = OnlineCourseCost::where('course_id', '=', $report->id)->sum('actual_amount');

            $activities = OnlineCourseActivity::getByCourse($report->id, 1);
            foreach ($activities as $activity){
                $activity_scorm = OnlineCourseActivityScorm::find($activity->subject_id);
                $scorm[] = $activity_scorm->getScoreScorm($report->user_id);
            }
        }else{
            $register_id = OfflineRegister::where('course_id', '=', $report->id)->pluck('id')->toArray();
            $student_cost = OfflineStudentCost::whereIn('register_id', $register_id)->sum('cost');
            $course_cost = OfflineCourseCost::where('course_id', '=', $report->id)->sum('actual_amount');

            $off_cost = $course_cost + $student_cost;

            $off_result = OfflineResult::where('course_id', '=', $report->id)
                ->where('user_id', '=', $report->user_id)
                ->where('result', '=', 1)
                ->first();

            $off_course = OfflineCourse::find($report->id);
            $training_form = TrainingForm::find($off_course->training_form_id);

            $training_form = $training_form ? $training_form->name : '';

            $indemnify = Indemnify::getCommitAmount($report->user_id, $report->id);

            $indem = ($indemnify ? $indemnify->commit_amount : '0') . ' VND';
        }

        $this->index++;
        $profile = Profile::find($report->user_id);
        $arr_unit = Unit::getTreeParentUnit($profile->unit_code);

        return [
            $this->index,
            $report->code,
            $report->lastname . ' '. $report->firstname,
            $report->title_name,
            $arr_unit ? $arr_unit[$profile->unit->level]->name : '',
            $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '',
            $arr_unit ? $arr_unit[2]->name : '',
            $report->course_code,
            $report->course_name,
            $report->course_type == 1 ? 'Offline' : 'Tập trung',
            $report->course_type == 1 ? '' : $report->training_unit,
            $report->course_type == 1 ? '' : $training_form,
            get_date($report->start_date, 'd/m/Y'),
            get_date($report->end_date, 'd/m/Y'),
            $report->course_type == 1 ? if_empty($onl_cost, 0) : if_empty($off_cost, 0),
            count($scorm) > 0 ? implode(' ', $scorm) : '',
            $report->score > 0 ? $report->score : '',
            $report->course_type == 1 ? ($onl_result ? 'x' : ''): ($off_result ? 'x' : ''),
            $report->course_type == 1 ? ($onl_result ? '' : 'x'): ($off_result ? '' : 'x'),
            $report->course_type == 1 ? '' : $indem,
        ];
    }
    public function headings(): array
    {
        return [
            [],
            [],
            [],
            [],
            ['QUÁ TRÌNH ĐÀO TẠO THEO '. ($this->unit_id ? 'ĐƠN VỊ' : 'HỌC VIÊN')],
            ['Từ: ' . $this->from_date .' - '. $this->to_date],
            ['Đơn vị', ($this->unit_id ? Unit::find($this->unit_id)->name : '')],
            ['STT', 'MSNV', 'Tên học viên', 'Chức danh', 'Đơn vị trực tiếp', 'Đơn vị gián tiếp', 'Công ty', trans('lacourse.course_code'), 'Khóa học', 'Hình thức', 'Đơn vị đào tạo', 'Hình thức đào tạo',
                'Thời gian', '', 'Chi phí', 'Kết quả', '', '', '', 'Cam kết đào tạo (Số)'],
            ['', '', '', '', '', '', '', '', '', '', '', '', 'Từ ngày', 'Đến ngày', '', 'Điểm bài học', 'Điểm thi', 'Đạt', 'Không đạt', '']
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
                $event->sheet->getDelegate()->mergeCells('A5:T5')->getStyle('A5')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A6:T6')->getStyle('A6')->applyFromArray($title);
                $event->sheet->getDelegate()->getStyle('A7')->applyFromArray($title);
                $event->sheet->getDelegate()->getStyle('A8:T9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->mergeCells('A8:A9');
                $event->sheet->getDelegate()->mergeCells('B8:B9');
                $event->sheet->getDelegate()->mergeCells('C8:C9');
                $event->sheet->getDelegate()->mergeCells('D8:D9');
                $event->sheet->getDelegate()->mergeCells('E8:E9');
                $event->sheet->getDelegate()->mergeCells('F8:F9');
                $event->sheet->getDelegate()->mergeCells('G8:G9');
                $event->sheet->getDelegate()->mergeCells('H8:H9');
                $event->sheet->getDelegate()->mergeCells('I8:I9');
                $event->sheet->getDelegate()->mergeCells('J8:J9');
                $event->sheet->getDelegate()->mergeCells('K8:K9');
                $event->sheet->getDelegate()->mergeCells('L8:L9');
                $event->sheet->getDelegate()->mergeCells('M8:N8');
                $event->sheet->getDelegate()->mergeCells('O8:O9');
                $event->sheet->getDelegate()->mergeCells('P8:S8');
                $event->sheet->getDelegate()->mergeCells('T8:T9');

                $event->sheet->getDelegate()->getStyle('A8:T'.(9 + $this->count).'')->applyFromArray([
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
