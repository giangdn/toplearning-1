<?php
namespace Modules\Report\Export;
use App\Config;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineResult;
use Modules\Report\Entities\BC05;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC05Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    private $course;
    private $course_type;
    public function __construct($param)
    {
        $this->course = $param->course;
        $this->course_type = $param->course_type;
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
    }

    public function query()
    {
        $query = BC05::sql($this->course_type,$this->course, $this->from_date, $this->to_date)->orderBy('id');
        return $query;
    }
    public function map($report): array
    {
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
            $result = OfflineResult::where('course_id', '=', $report->course_id)->where('user_id', '=', $report->user_id)->first();
        }

        $score_final = $score_scorm ? ($score_scorm + $report->score)/2 : $report->score;

        $this->index++;
        $profile = Profile::find($report->user_id);
        $arr_unit = Unit::getTreeParentUnit($profile->unit_code);

        return [
            $this->index,
            $report->code,
            $report->full_name,
            $report->title_name,
            $arr_unit ? $arr_unit[$profile->unit->level]->name : '',
            $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '',
            $arr_unit ? $arr_unit[2]->name : '',
            get_date($report->start_date),
            get_date($report->complete_date),
            $score_scorm ? number_format((float)$score_scorm, 2) : '',
            number_format($report->score, 2),
            number_format($score_final, 2),
            ($result && $result->result == 1) ? 'Đạt' : 'Không đạt',
            $report->note,
        ];
    }
    public function headings(): array
    {
        $teacher = '';
        $course = BC05::getCourseInfo($this->course, $this->course_type);
        if ($this->course_type == 2){
            $teacher = OfflineTeacher::getTeachers($this->course);
        }
        $course_time = preg_replace("/[^0-9]/", '', $course->course_time);
        $course_time_unit = preg_replace("/[^a-z]/", '', $course->course_time);
        switch ($course_time_unit){
            case 'day': $time_unit = 'Ngày'; break;
            case 'session': $time_unit = 'Buổi'; break;
            default : $time_unit = 'Giờ'; break;
        }

        return [
            [],
            [],
            [],
            [],
            ['DANH SÁCH HỌC VIÊN THAM GIA KHÓA ĐÀO TẠO '],
            [$course->name],
            ['Hình thức ', $course->course_type],
            ['Thời lượng ', $course_time ? ($course_time . ' ' . $time_unit) : ''],
            ['Thời gian ', get_date($course->start_date).' - '.get_date($course->end_date)],
            ['Đơn vị đào tạo ', $course->training_unit],
            ['Giảng viên ', $teacher ? $teacher : ''],
            [],
            [
                'STT',
                'Mã nhân viên',
                'Họ Tên',
                'Chức danh',
                'Đơn vị trực tiếp',
                'Đơn vị gián tiếp 1',
                'Công ty',
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
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],

                    ],
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()
                    ->getStyle("A".($this->startRow()-1).":N".($this->startRow()-1))
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->mergeCells('A5:N5')->getStyle('A5')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A6:N6')->getStyle('A6')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A13:N'.(13 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
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
        return 14;
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
