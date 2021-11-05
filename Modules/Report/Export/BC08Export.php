<?php
namespace Modules\Report\Export;
use App\Config;
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
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Report\Entities\BC08;
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

class BC08Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    private $course;
    public function __construct($param)
    {
        $this->course = $param->course;
    }

    public function query()
    {
        $query = BC08::sql($this->course)->orderBy('id');
        return $query;
    }
    public function map($report): array
    {
        $this->index++;
        $profile = Profile::find($report->user_id);
        $arr_unit = Unit::getTreeParentUnit($profile->unit_code);

        $score_scorm = '';
        $scorm = 0;

        $activities = OnlineCourseActivity::getByCourse($report->course_id, 1);
        if (count($activities) > 0){
            foreach ($activities as $activity){
                $activity_scorm = OnlineCourseActivityScorm::find($activity->subject_id);
                $score = $activity_scorm->getScoreScorm($report->user_id);
                $scorm += $score;
            }
            $score_scorm = number_format($scorm/(count($activities) > 0 ? count($activities) : 1), 2);
        }
        $score_final = $score_scorm ? ($score_scorm + $report->score)/2 : $report->score;

        if ($report->result == 1){
            $report->pass = 'X';
        }else{
            $report->fail = 'X';
        }

        return [
            $this->index,
            $report->code,
            $report->full_name,
            $report->title_name,
            $arr_unit ? $arr_unit[$profile->unit->level]->name : '',
            $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '',
            $arr_unit ? $arr_unit[2]->name : '',
            $report->email,
            $score_scorm,
            number_format($report->score,2),
            number_format($score_final, 2),
            $report->pass,
            $report->fail,
            ''
        ];
    }
    public function headings(): array
    {
        $course = BC08::getCourseInfo($this->course);

        $course_time = preg_replace("/[^0-9]./", '', $course->course_time);
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
            [],
            ['BÁO CÁO KẾT QUẢ KHÓA HỌC ELEARNING'],
            [trans('lacourse.course_code').': ', $course->code],
            [trans('lacourse.course_name').': ', $course->name],
            ['Hình thức: ', 'Elearning'],
            ['Thời lượng: ', $course_time ? ($course_time . ' ' . $time_unit) : ''],
            ['Thời gian: ', get_date($course->start_date).' - '.get_date($course->end_date)],
            ['Địa điểm: ', $course->training_location],
            ['Chi phí: ', $course->cost_class],
            [
                'STT',
                'Mã HV',
                'Họ và tên',
                'Chức danh',
                'Đơn vị trực tiếp',
                'Đơn vị gián tiếp 1',
                'Công ty',
                'Email',
                'Điểm bài học',
                'Điểm thi',
                'Điểm tổng kết',
                'Đạt',
                'Không đạt',
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
                    ->getStyle("A".($this->startRow()-1).":N".($this->startRow()-1))
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->mergeCells('A6:N6')->getStyle('A6')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A14:N'.(14 + $this->index))
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
        return 15;
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
