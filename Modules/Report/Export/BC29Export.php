<?php
namespace Modules\Report\Export;
use App\Models\Categories\TrainingForm;
use function GuzzleHttp\Psr7\normalize_header;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Rating\Entities\RatingCourse;
use Modules\Report\Entities\BC11;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class BC29Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow
{
    use Exportable;
    protected $index = 0;
    private $course;
    private $course_type;
    protected $count = 0;
    public function __construct($param)
    {
        $this->course = $param->course;
        $this->course_type = $param->type;
    }

    public function query()
    {
        $query = BC11::sql($this->course, $this->course_type)->orderBy('id', 'ASC');

        return $query;
    }

    public function map($report): array
    {
        $this->index++;
        $title = [];
        $title1 = [];
        $title2 = [];

        $title[] = $this->index;
        $title[] = $report->name;

        $title1[] = '';
        $title1[] = '';

        $title2[] = '';
        $title2[] = '';

        $answer_query = \DB::query()
            ->from('el_rating_question_answer')
            ->where('question_id', '=', $report->id)
            ->get();

        $count = 0;
        foreach ($answer_query as $item){
            $answer = DB::query()
                ->from('el_rating_course_answer')
                ->where('answer_id', '=', $item->id)
                ->where('is_check', '=', 1)
                ->count();

            $count += $answer;
        }

        $this->count = $count;

        foreach ($answer_query as $item){
            $answer = DB::query()
                ->from('el_rating_course_answer')
                ->where('answer_id', '=', $item->id)
                ->where('is_check', '=', 1)
                ->count();

            $title[] = $item->name;
            $title[] = 'Số lượng';

            $title1[] = 'Số phiếu';
            $title1[] = $answer < 10 ? '0'.$answer : $answer;

            $title2[] = 'Tỷ lệ';
            $title2[] = number_format(($answer/$this->count)*100, 0). ' %';
        }

        return [
            $title,
            $title1,
            $title2,
            []
        ];
    }

    public function headings(): array
    {
        if ($this->course_type == 1){
            $course = OnlineCourse::find($this->course);

            $query2 =\DB::query();
            $query2->select(['b.name AS title_name'])
                ->from('el_online_object as a')
                ->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id')
                ->where('a.course_id', '=', $course->id)
                ->where('a.type', '=', 1);
            $object = $query2->get();

            $objects = [];
            foreach ($object as $item){
                $objects[] = $item->title_name;
            }

        }
        else{
            $course = OfflineCourse::find($this->course);
            $trainin_form = TrainingForm::find($course->training_form_id);

            $sub = DB::query();
            $sub->from('el_offline_schedule')
                ->groupBy(['course_id','teacher_main_id'])
                ->selectRaw('course_id, teacher_main_id');

            $query1 =\DB::query();
            $query1->from('el_training_teacher as a')
                ->joinSub($sub,'b', function ($join){
                    $join->on('a.id','=','b.teacher_main_id');
                })
                ->where('b.course_id', '=', $course->id);
            $teacher = $query1->get();

            $teachers = [];
            foreach ($teacher as $item){
                $teachers[] = $item->code . ' - ' . $item->name;
            }

            $query2 =\DB::query();
            $query2->select(['b.name AS title_name'])
                ->from('el_offline_object as a')
                ->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id')
                ->where('a.course_id', '=', $course->id)
                ->where('a.type', '=', 1);
            $object = $query2->get();

            $objects = [];
            foreach ($object as $item){
                $objects[] = $item->title_name;
            }
        }

        $query3 = DB::query();
        $query3->select(['id'])
            ->from('el_offline_register')
            ->where('course_id', '=', $course->id)
            ->where('status', '=', 1);
        $count_regid = $query3->count();

        $query4 = DB::query();
        $query4->select(['id'])
            ->from('el_rating_course')
            ->where('course_id', '=', $this->course)
            ->where('type', '=', $this->course_type)
            ->where('send', '=', 1);
        $count_send_rating = $query4->count();

        return [
            ['BÁO CÁO ĐÁNH GIÁ'],
            ['Khóa học','', $course->name],
            ['Hình thức đào tạo', '', $this->course_type == 2 ? $trainin_form->name : ''],
            ['Thời lượng', '', $course->course_time],
            ['Ngày bắt đầu', '', get_date($course->start_date, 'd/m/Y')],
            ['Ngày kết thúc', '', get_date($course->end_date, 'd/m/Y')],
            ['Đơn vị đào tạo', '', $this->course_type == 2 ? $course->training_unit : ''],
            ['Giảng viên', '',  $this->course_type == 2 ? implode('; ', $teachers) : ''],
            ['Đối tượng tham gia', '', implode('; ', $objects)],
            ['Số lượng tham gia', '', $count_regid ? $count_regid : 0],
            ['Số phiếu đánh giá', '', $count_send_rating],
            [],
            ['STT', 'Nội dung'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:F11')->applyFromArray([
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
        return 2;
    }
}
