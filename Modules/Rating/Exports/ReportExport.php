<?php
namespace Modules\Rating\Exports;

use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRatingLevel;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRatingLevel;
use Modules\Rating\Entities\CourseRatingLevel;
use Modules\Rating\Entities\RatingLevelCourse;
use Modules\Rating\Entities\RatingLevelCourseExport;
use Modules\Rating\Entities\RatingLevels;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $count = 0;
    protected $index = 0;
    protected $num_char = 5;
    protected $title_arr = [];

    public function __construct($course_id, $course_type, $course_rating_level_id, $course_rating_level_object_id = 0)
    {
        $this->course_id = $course_id;
        $this->course_type = $course_type;
        $this->course_rating_level_id = $course_rating_level_id;
        $this->course_rating_level_object_id = $course_rating_level_object_id;
    }

    public function map($report): array
    {
        $this->index++;
        $profile = Profile::whereUserId($report->user_id)->first();

        $answer_name = [];
        $answer_name[] = $this->index;
        $answer_name[] = $profile->code;
        $answer_name[] = $profile->full_name;
        $answer_name[] = @$profile->unit->name;
        $answer_name[] = @$profile->title->name;

        foreach ($this->title_arr as $title){
            $query = RatingLevelCourseExport::query()
                ->where('course_id', '=', $report->course_id)
                ->where('course_type', '=', $report->course_type)
                ->where('course_rating_level_id', '=', $report->course_rating_level_id)
                ->where('user_id', '=', $report->user_id)
                ->where('user_type', '=', $report->user_type)
                ->where('title', '=', $title)
                ->first();

            $answer_name[] = @$query->content;
        }

        return [
            $answer_name,
        ];
    }

    public function query(){
        $query = RatingLevelCourse::query()
            ->where('course_id', '=', $this->course_id)
            ->where('course_type', '=', $this->course_type)
            ->where('course_rating_level_id', '=', $this->course_rating_level_id)
            ->where('course_rating_level_object_id', '=', $this->course_rating_level_object_id)
            ->where('send', '=', 1)
            ->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;

    }

    public function headings(): array
    {
        $title_course = [];
        $title_course_time = [];
        if ($this->course_type == 1){
            $course = OnlineCourse::find($this->course_id);
            $course_rating_level = OnlineRatingLevel::find($this->course_rating_level_id);

            $title_course[] = 'Khóa học: ';
            $title_course[] = '('. $course->code .') '. $course->name;

            $title_course_time[] = 'Thời gian khóa học: ';
            $title_course_time[] = get_date($course->start_date) . ($course->end_date ? ' đến '. get_date($course->end_date) : '');
        }
        if ($this->course_type == 2){
            $course = OfflineCourse::find($this->course_id);
            $course_rating_level = OfflineRatingLevel::find($this->course_rating_level_id);

            $title_course[] = 'Khóa học: ';
            $title_course[] = '('. $course->code .') '. $course->name;

            $title_course_time[] = 'Thời gian khóa học: ';
            $title_course_time[] = get_date($course->start_date) . ($course->end_date ? ' đến '. get_date($course->end_date) : '');
        }
        if ($this->course_type == 3){
            $course = RatingLevels::find($this->course_id);
            $course_rating_level = CourseRatingLevel::find($this->course_rating_level_id);

            $title_course[] = 'Kỳ đánh giá: ';
            $title_course[] = $course->name;

            $title_course_time[] = '';
            $title_course_time[] = '';
        }

        $getuser = RatingLevelCourse::query()
            ->where('course_id', '=', $this->course_id)
            ->where('course_type', '=', $this->course_type)
            ->where('course_rating_level_id', '=', $this->course_rating_level_id)
            ->where('send', '=', 1)
            ->first();

        $query = RatingLevelCourseExport::query()
            ->where('course_id', '=', $this->course_id)
            ->where('course_type', '=', $this->course_type)
            ->where('course_rating_level_id', '=', $this->course_rating_level_id)
            ->where('user_id', '=', @$getuser->user_id);
        $questions = $query->get(['title']);

        $title = [];
        $title[] = 'STT';
        $title[] = 'Mã nhân viên';
        $title[] = 'Họ tên';
        $title[] = 'Đơn vị';
        $title[] = 'Chức danh';
        foreach ($questions as $item) {
            $title[] = $item->title;
            $this->title_arr[] = $item->title;

            $this->num_char += 1;
        }

        return [
            ['Báo cáo kết quả đánh giá cấp độ'],
            ['Tên đánh giá: ', $course_rating_level->rating_name],
            ['Cấp độ: ', $course_rating_level->level.' '],
            $title_course,
            $title_course_time,
            [''],

            $title,
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
                        ],
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'size'      =>  14,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $event->sheet->mergeCells('A1:F1');

                $event->sheet->getDelegate()->getStyle('A1:E1')->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');

                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                if ($this->num_char > 26){
                    $num = floor($this->num_char/26);
                    $num_1 = $this->num_char - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($this->num_char - 1)];
                }

                $event->sheet->getDelegate()->getStyle('A7:'.$char.''.(7 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },

        ];
    }

}
