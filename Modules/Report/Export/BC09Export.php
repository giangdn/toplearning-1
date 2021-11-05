<?php
namespace Modules\Report\Export;
use App\Config;
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
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Rating\Entities\RatingCourse;
use Modules\Report\Entities\BC09;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC09Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    private $course;
    private $course_type;
    protected $count = 0;
    protected $count_title = 7;
    protected $char = 'G';

    public function __construct($param)
    {
        $this->course = $param->course;
        $this->course_type = $param->type;
    }

    public function query()
    {
        $query = BC09::sql($this->course, $this->course_type)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($report): array
    {
        $user_type = $report->user_type;
        $this->index++;
        if ($user_type == 1){
            $profile = Profile::find($report->user_id);
            $arr_unit = Unit::getTreeParentUnit($profile->unit_code);
        }else{
            $profile = QuizUserSecondary::find($report->user_id);
            $arr_unit = '';
        }

        $answer_name = [];
        $answer_name[] = $this->index;
        $answer_name[] = $user_type == 1 ? $report->code : $report->user_secon_code;
        $answer_name[] = $user_type == 1 ? $report->lastname . ' ' . $report->firstname : $report->secondary_name;
        $answer_name[] = $report->title_name;
        $answer_name[] = $arr_unit ? $arr_unit[$profile->unit->level]->name : '';
        $answer_name[] = $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '';
        $answer_name[] = $arr_unit ? $arr_unit[2]->name : '';

        $query = DB::query();
        $query->select(['a.id', 'a.type'])
            ->from('el_rating_course_question AS a')
            ->leftJoin('el_rating_course_category AS b', 'b.id', '=', 'a.course_category_id')
            ->where('b.rating_course_id', '=', $report->id);
        $questions = $query->get();

        foreach ($questions as $question) {
            if ($question->type == 'essay'){
                /*$answer_query = \DB::query()
                    ->select(['a.answer_essay'])
                    ->from('el_rating_course_question AS a')
                    ->join('el_rating_course_category AS b', 'b.id', '=', 'a.course_category_id')
                    ->join('el_rating_course AS c', 'c.id', '=', 'b.rating_course_id')
                    ->where('a.id', '=', $question->id)
                    ->where('c.user_id', '=', $report->user_id)
                    ->where('c.course_id', '=', $this->course)
                    ->first();*/
                $answer_query = \DB::query()
                    ->select(['a.answer_essay'])
                    ->from('el_rating_course_question AS a')
                    ->where('a.id', '=', $question->id)
                    ->first();
                $answer_name[] = $answer_query ? $answer_query->answer_essay : '';
            }else{
                $answer_query = \DB::query()
                    ->select(['a.answer_name', 'a.text_answer', 'a.is_text'])
                    ->from('el_rating_course_answer AS a')
                    ->where('a.course_question_id', '=', $question->id)
                    ->where('a.is_check', '=', 1)
                    ->first();
                if ($answer_query){
                    if ($answer_query->is_text == 1){
                        $answer_name[] = implode(';', [$answer_query->answer_name . ' - ' . $answer_query->text_answer]);
                    }else{
                        $answer_name[] = $answer_query->answer_name;
                    }
                }else{
                    $answer_name[] = '';
                }


                /*$answer_query = \DB::query()
                    ->select(['b.answer_name', 'b.text_answer', 'b.is_text'])
                    ->from('el_rating_course_question AS a')
                    ->join('el_rating_course_answer AS b', 'b.course_question_id', '=', 'a.id')
                    ->join('el_rating_course_category AS c', 'c.id', '=', 'a.course_category_id')
                    ->join('el_rating_course AS d', 'd.id', '=', 'c.rating_course_id')
                    ->where('a.id', '=', $question->id)
                    ->where('d.user_id', '=', $report->user_id)
                    ->where('d.course_id', '=', $this->course)
                    ->where('b.is_check', '=', 1)
                    ->get();
                foreach ($answer_query as $item){
                    if ($item->is_text == 1){
                        $answer_name[] = implode(';', [$item->answer_name . ' - ' . $item->text_answer]);
                    }else{
                        $answer_name[] = $item->answer_name;
                    }
                }*/
            }
        }

        return [
            $answer_name,
        ];
    }
    public function headings(): array
    {
        $rating = RatingCourse::where('course_id', '=', $this->course)->where('type', '=', $this->course_type)->first();
        if ($this->course_type == 1){
            $course = OnlineCourse::find($this->course);
        }else{
            $course = OfflineCourse::find($this->course);
        }

        $query = \DB::query();
        $query->select(['a.name']);
        $query->from('el_rating_question AS a')
            ->join('el_rating_category AS b', 'b.id', '=', 'a.category_id')
            ->where('b.template_id', '=', $rating['template_id']);
        $questions = $query->get();

        $title = [];
        $title[] = 'STT';
        $title[] = 'Mã nhân viên';
        $title[] = 'Họ tên';
        $title[] = 'Chức danh';
        $title[] = 'Đơn vị trực tiếp';
        $title[] = 'Đơn vị gián tiếp cấp 1';
        $title[] = 'Công ty';

        foreach ($questions as $item) {
            $title[] = $item->name;
            $this->count_title += 1;
        }
        return [
            [],
            [],
            [],
            [],
            [],
            [],
            ['Đánh giá sau khóa học'],
            [trans('lacourse.course_code'),'', $course->code],
            [trans('lacourse.course_name'),'', $course->name],
            ['Ngày bắt đầu','', get_date($course->start_date, 'd/m/Y')],
            ['Ngày kết thúc','', get_date($course->end_date, 'd/m/Y')],
            [],
            $title,
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $header = [
                    'borders' => [
                        'allBorders' => [
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
                $event->sheet->getDelegate()->mergeCells('A7:G7')->getStyle('A7')->applyFromArray($title);
                $event->sheet->getDelegate()->getStyle('A8:B11')->applyFromArray($title)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->mergeCells('C8:G8');
                $event->sheet->getDelegate()->mergeCells('C9:G9');
                $event->sheet->getDelegate()->mergeCells('C10:G10');
                $event->sheet->getDelegate()->mergeCells('C11:G11');

                $event->sheet->getDelegate()->mergeCells('A8:B8');
                $event->sheet->getDelegate()->mergeCells('A9:B9');
                $event->sheet->getDelegate()->mergeCells('A10:B10');
                $event->sheet->getDelegate()->mergeCells('A11:B11');

                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                if ($this->count_title > 26){
                    $num = floor($this->count_title/26);
                    $num_1 = $this->count_title - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($this->count_title - 1)];
                }

                $event->sheet->getDelegate()
                    ->getStyle('A13:'.$char.'13')
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A'.(13 + $this->count).':'.$char.(13 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('A7:G11')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
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
