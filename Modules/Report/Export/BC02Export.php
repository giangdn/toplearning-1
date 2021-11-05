<?php
namespace Modules\Report\Export;
use App\Config;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineResult;
use Modules\Report\Entities\BC02;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC02Export implements  FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    private $date;
    public function __construct($param)
    {
        $this->date = $param->date;
    }
    public function map($report): array
    {
        $this->index++;
        $profile = Profile::find($report->user_id);
        $arr_unit = Unit::getTreeParentUnit($profile->unit_code);

        $training_form = '';
        $score_scorm = '';
        $activities = OnlineCourseActivity::getByCourse($report->course_id, 1);
        if ($report->type == 1){
            $scorm = 0;
            if (count($activities) > 0){
                foreach ($activities as $activity){
                    $activity_scorm = OnlineCourseActivityScorm::find($activity->subject_id);
                    $score = $activity_scorm->getScoreScorm($report->user_id);
                    $scorm += $score;
                }
                $score_scorm = $scorm/(count($activities) > 0 ? count($activities) : 1);
            }
            $result = OnlineResult::where('course_id', '=', $report->course_id)->where('user_id', '=', $report->user_id)->first();
        }else{
            $offline = OfflineCourse::find($report->course_id);
            $training_form = TrainingForm::find($offline->training_form_id);
            $result = OfflineResult::where('course_id', '=', $report->course_id)->where('user_id', '=', $report->user_id)->first();
        }

        $score_final = $score_scorm ? ($score_scorm + $report->score)/2 : $report->score;
        $teacher = OfflineTeacher::getTeachers($report->course_id);

        $indemnify = Indemnify::where('user_id', '=', $report->user_id)->where('course_id', '=', $report->course_id)->first();

        $course_time = preg_replace("/[^0-9]./", '', $report->course_time);
        $course_time_unit = preg_replace("/[^a-z]/", '', $report->course_time);
        switch ($course_time_unit){
            case 'day': $time_unit = 'Ngày'; break;
            case 'session': $time_unit = 'Buổi'; break;
            default : $time_unit = 'Giờ'; break;
        }

        return [
            $this->index,
            $report->code,
            $report->lastname . ' ' .$report->firstname,
            $report->title_name,
            $arr_unit ? $arr_unit[$profile->unit->level]->name : '',
            $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '',
            $arr_unit ? $arr_unit[2]->name : '',
            $report->course_code,
            $report->course_name,
            $report->course_type,
            $course_time ? $course_time . ' ' . $time_unit : '',
            get_date($report->start_date, 'd/m/Y'),
            get_date($report->end_date, 'd/m/Y'),
            $training_form ? $training_form->name : '',
            $report->training_unit,
            $teacher,
            get_date($report->commit_date, 'd/m/Y'),
            $indemnify ? $indemnify->commit_date : '',
            get_date($report->start_date, 'd/m/Y'),
            get_date($report->end_date, 'd/m/Y'),
            $score_scorm ? number_format((float) $score_scorm, 2) : '',
            number_format($report->score, 2),
            number_format($score_final, 2),
            ($result && $result->result == 1) ? 'Đạt' : 'Không đạt',
            $report->note,
        ];
    }

    public function query()
    {
        $month = Str::before($this->date, '/') ;
        $year = Str::after($this->date, '/');
        $query = BC02::sql($month, $year);
        $query->orderBy('a.id','asc');
        return $query;
    }
    public function headings(): array
    {
        return [
            [],
            [],
            ['DANH SÁCH HỌC VIÊN THAM GIA CÁC KHÓA ĐÀO TẠO '],
            ['Tháng '.$this->date],
            [],
            [],
            [
                'STT',
                'Mã nhân viên',
                'Họ Tên',
                'Chức danh',
                'Đơn vị trực tiếp',
                'Đơn vị gián tiếp 1',
                'Công ty',
                trans('lacourse.course_code'),
               trans('lacourse.course_name'),
                'Hình thức đào tạo',
                'Thời lượng đào tạo',
                'Ngày bắt đầu',
                'Ngày kết thúc',
                'Loại hình đào tạo',
                'Đơn vị đào tạo',
                'Giảng viên',
                'Ngày bắt đầu cam kết',
                'Thời gian cam kết (Tháng)',
                'Ngày tham gia',
                'Ngày hoàn thành',
                'Điểm bài học',
                'Điểm thi',
                'Điểm tổng kết',
                'Kết quả',
                'Ghi chú',
            ]
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $header = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],

                    ],
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
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
                $event->sheet->getDelegate()
                    ->getStyle("A7:Y7")
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->mergeCells('A3:Y3')->getStyle('A3')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A4:Y4')->getStyle('A4')->applyFromArray($title);
                $event->sheet->getDelegate()->getStyle('A7:Y'.(7 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],

                        ],
                        'font' => [
                            'name' => 'Arial',
                            'size' =>  12,
                        ],
                    ]);
            },

        ];
    }
    public function startRow(): int
    {
        return 8;
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
