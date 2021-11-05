<?php


namespace Modules\Potential\Exports;

use App\Models\Categories\Titles;
use App\Profile;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\Potential\Entities\Potential;
use Modules\Potential\Entities\PotentialRoadmap;
use Modules\Quiz\Entities\QuizResult;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportCourse implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{

    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function map($subject): array
    {
        $this->index++;
        $course = $this->getCourse($subject->training_program_id, $subject->subject_id, $subject->training_form, $this->user_id);
        if (!is_null($course)){
            $result_course = $this->getCourseResult($this->user_id, $course->id, $course->course_type);
        }

        return [
            $this->index,
            $subject->code,
            $subject->name,
            $course ? get_date($course->start_date) : '',
            $course ? get_date($course->end_date) : '',
            isset($result_course) ?  $result_course->reexamine ? $result_course->reexamine : $result_course->grade : '',
            isset($result_course) ? ($result_course->result == 1 ? 'Hoàn thành' : 'Chưa hoàn thành') : 'Chưa hoàn thành',
        ];
    }

    public function query()
    {
        $potential = Potential::where('user_id', '=', $this->user_id)->first();
        $profile = Profile::find($potential->user_id);
        $title = Titles::where('code', '=', $profile->title_code)->first();

        $query = PotentialRoadmap::query();
        $query->select([
            'a.subject_id',
            'a.training_program_id',
            'a.training_form',
            'b.name',
            'b.code'
        ])->from('el_potential_roadmap AS a')
            ->leftJoin('el_subject AS b', function ($sub){
                $sub->on('a.training_program_id', '=', 'b.training_program_id');
                $sub->on('a.subject_id', '=', 'b.id');
            })
            ->where('b.status', '=', 1)
            ->where('a.title_id', '=', $title->id)
            ->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $profile = Profile::find($this->user_id);
        return [
            ['Khóa học của nhân viên '. $profile->lastname . ' ' . $profile->firstname],
            [
                'STT',
                'Mã học phần',
                'Tên học phần',
                'Ngày bắt đầu',
                'Ngày kết thúc',
                'Điểm thi',
                'Kết quả'
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:G'.(2 + $this->count).'')->applyFromArray([
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
                    ],
                ]);
            },
        ];
    }
    public function getCourse($training_program_id, $subject_id, $cours_type, $user_id){
        $query = \DB::query();
        $query->select('a.id', 'a.course_type', 'a.start_date', 'a.end_date')
            ->from('el_course_view as a')
            ->leftJoin('el_course_register_view as b', function ($sub){
                $sub->on('b.course_id', '=', 'a.course_id');
                $sub->on('b.course_type', '=', 'a.course_type');
            })
            ->where('b.user_id', '=', $user_id)
            ->where('a.training_program_id', '=', $training_program_id)
            ->where('a.subject_id', '=', $subject_id)
            ->where('a.course_type', '=', $cours_type)
            ->where('a.status', '=', 1)
            ->where('b.status', '=', 1);

        return $query->first();
    }

    public function getCourseResult($user_id, $course_id, $course_type){
        if ($course_type == 1) {
            $onl = OnlineCourseActivity::where('course_id', '=', $course_id)
                ->where('activity_id', '=', 2)->first();

            $result = null;
            if ($onl->subject_id){
                $result = QuizResult::where('quiz_id', '=', $onl->subject_id)
                    ->where('user_id', '=', $user_id)->first();
            }

        } else {
            $off = OfflineCourse::find($course_id);
            $result = null;
            if ($off->quiz_id){
                $result = QuizResult::where('quiz_id', '=', $off->quiz_id)
                    ->where('user_id', '=', $user_id)->first();
            }
        }

        return $result;
    }
}
