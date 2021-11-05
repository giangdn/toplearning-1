<?php
namespace Modules\Online\Exports;

use App\Profile;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineResult;
use Modules\Online\Entities\OnlineCourse;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithDrawings;
use App\Config;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportResultOnline implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $count = 0;
    protected $index = 0;
    protected $num_char = 6;

    public function __construct($course_id)
    {
        $this->course_id = $course_id;
    }

    public function map($report): array
    {
        $this->index++;

        $answer_name = [];
        $answer_name[] = $this->index;
        $answer_name[] = $report->code;
        $answer_name[] = $report->email;
        $answer_name[] = $report->full_name;

        $activities = OnlineCourseActivity::getByCourse($report->course_id);
        foreach ($activities as $activity){
            $check_complete = $activity->isComplete($report->user_id, $report->user_type);

            $answer_name[] = ($check_complete ? trans("backend.finish") : trans("backend.incomplete"));

            if ($activity->activity_id == 1) {
                $activity_scorm = OnlineCourseActivityScorm::find($activity->subject_id);
                $score = $activity_scorm->getScoreScorm($report->user_id,  $report->user_type);
                // $report->{'score_'. $activity->id} = ($score ? number_format($score, 2) : '');
                $answer_name[] = ($score ? number_format($score, 2) : '');
            }
        }
        $result = OnlineResult::where('user_id', '=', $report->user_id)
            ->where('user_type', '=', $report->user_type)
            ->where('course_id', '=', $report->course_id)
            ->first();

        $answer_name[] =  ($result && $result->score > 0) ? number_format($result->score, 2) : '';
        $answer_name[] =  $result ? $result->result == 1 ? trans("backend.achieved") : trans("backend.not_achieved") : '';
        return [
            $answer_name,
        ];
    }

    public function query(){
        $query = OnlineRegister::query();
        $query->select([
            'a.*',
            'b.full_name',
            'b.code',
            'b.email',
            'd.code as second_code',
            'd.name as second_name',
            'd.email as second_email',
        ]);
        $query->from('el_online_register AS a');
        $query->leftjoin('el_profile_view AS b', function ($sub){
            $sub->on('b.user_id', '=', 'a.user_id')
                ->where('a.user_type', '=', 1);
        });
        $query->leftjoin('el_quiz_user_secondary AS d', function ($sub){
            $sub->on('d.id', '=', 'a.user_id')
                ->where('a.user_type', '=', 2);
        });
        $query->where('a.course_id', '=', $this->course_id);
        $query->where('a.status', '=', 1);
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;

    }

    public function headings(): array
    {
        $activities = OnlineCourseActivity::getByCourse($this->course_id);
        $course = OnlineCourse::find($this->course_id);
        
        $title = [];
        $title[] = 'STT';
        $title[] = 'Mã nhân viên';
        $title[] = 'email';
        $title[] = 'Họ tên';
        foreach ($activities as $activity){
            if ($activity->activity_id == 1) {
                $title[] = $activity->name;
                $title[] = 'Điểm '. $activity->name;
                $this->num_char += 2;
            } else {
                $title[] = $activity->name;
                $this->num_char += 1;
            }
        }
        $title[] = 'Điểm';
        $title[] = 'Kết quả';

        return [
            [],
            [],
            [],
            [],
            [],
            ['Kết quả sau khóa học'],
            [trans('lacourse.course_code').': '.$course->code],
            [trans('lacourse.course_name').': '.$course->name],
            ['Ngày bắt đầu: '. date("d-m-Y", strtotime($course->start_date))],
            ['Ngày kết thúc: '.($course->end_date ? date("d-m-Y", strtotime($course->end_date)) : '')],
            [],
            $title,
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
                if ($this->num_char > 26){
                    $num = floor($this->num_char/26);
                    $num_1 = $this->num_char - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($this->num_char - 1)];
                }

                $event->sheet->getDelegate()->mergeCells('A6:'.$char.'6')->getStyle('A6')->applyFromArray($title);

                $event->sheet->getDelegate()->mergeCells('A7:F7');
                $event->sheet->getDelegate()->mergeCells('A8:F8');
                $event->sheet->getDelegate()->mergeCells('A9:F9');
                $event->sheet->getDelegate()->mergeCells('A10:F10');

                $event->sheet->getDelegate()->getStyle('A12:'.$char.'12')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A12:'.$char.''.(12 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },

        ];
    }

    public function startRow(): int
    {
        return 12;
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
