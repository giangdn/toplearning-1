<?php
namespace Modules\TrainingUnit\Exports;

use App\Parameter;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingTeacher;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\ManagerCourse\Entities\ManagerCourseChild;
use Modules\ManagerCourse\Entities\ManagerCourseComplete;
use Modules\ManagerCourse\Entities\ManagerCourseRegister;
use Modules\ManagerCourse\Entities\ManagerCourseResult;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ResultExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function map($result): array
    {
        $subjects = $this->getSubject($result->training_program_id);

        $column = [
            [
                $result->name,
                'Khóa học',
                '',
                '',
                '',
                '',
                'Đánh giá kỹ năng',
                '',
                '',
                '',
                '',
                'Thi offline',
                '',
                '',
                '',
                '',
                'Kết quả chung',
            ],
            [
                '',
                'Loại',
                'Ngày hoàn thành',
                'Điểm',
                'Giảng viên',
                'Kết quả',
                'Loại',
                'Ngày hoàn thành',
                'Điểm',
                'Giảng viên',
                'Kết quả',
                'Loại',
                'Ngày hoàn thành',
                'Điểm',
                'Giảng viên',
                'Kết quả',
                '',
            ]
        ];

        foreach ($subjects as $subject){
            $complete = $this->getResult($this->user_id, $subject->subject_id);

            $content[] = $subject->name;
            for ($i = 1; $i <= 3; $i++){
                $teachers = $complete ? $this->getTeacher($complete->{'teacher_'.$i}) : '';
                $param = Parameter::where('type', '=', $i)->first();

                $content[] = $complete ? $complete->{'type_'.$i} == 1 ? 'Offline' : 'Online' : '';
                $content[] = $complete ? get_date($complete->{'date_complete_'.$i}) : '';
                $content[] = $complete ? $complete->{'score_'.$i} : '';
                $content[] = $complete ? $teachers ? $teachers->name : '' : '';
                $content[] = $complete ? $complete->{'score_'.$i} >= $param->score ? 'Hoàn thành' : 'Không hoàn thành' : '';
            }
            $content[] = $complete ? $complete->result == 1 ? 'Hoàn thành' : 'Không hoàn thành' : '';

            $column[] = $content;
            $content = [];
        }

        $column[] = $content;

        return $column;
    }

    public function query(){
        $profile = Profile::find($this->user_id);
        $titles = Titles::where('code', '=', $profile->title_code)->first();

        $query = TrainingRoadmap::query()
            ->select([
                'a.training_program_id',
                'b.name',
            ])
            ->from('el_trainingroadmap as a')
            ->leftJoin('el_training_program as b', 'b.id', '=', 'a.training_program_id')
            ->where('a.title_id', '=', $titles->id)
            ->groupBy(['a.training_program_id', 'b.name'])
            ->orderBy('a.training_program_id', 'ASC');

        $this->index = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $profile = Profile::find($this->user_id);

        return [
            [
                'HỒ SƠ PHÁT TRIỂN QUẢN LÝ'
            ],
            [
                'Mã nhân viên: ' . $profile->code .' __ '. 'Họ và tên: ' . $profile->lastname . ' ' . $profile->firstname
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:Q1')->getStyle('A1:Q1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $event->sheet->getDelegate()->mergeCells('A2:Q2')->getStyle('A2:Q2')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $count = 0;
                $query = $this->query()->get();
                foreach ($query as $key => $item){
                    if ($key == 0){
                        $event->sheet->getDelegate()->mergeCells('A3:A4');
                        $event->sheet->getDelegate()->mergeCells('B3:F3');
                        $event->sheet->getDelegate()->mergeCells('G3:K3');
                        $event->sheet->getDelegate()->mergeCells('L3:P3');
                        $event->sheet->getDelegate()->mergeCells('Q3:Q4');
                    }else{
                        $subject = $this->getSubject($query[$key-1]->training_program_id);

                        $event->sheet->getDelegate()->mergeCells('A'.(5 + $subject->count() + 1).':A'.(5 + $subject->count() + 2));
                        $event->sheet->getDelegate()->mergeCells('B'.(5 + $subject->count() + 1).':F'.(5 + $subject->count() + 1));
                        $event->sheet->getDelegate()->mergeCells('G'.(5 + $subject->count() + 1).':K'.(5 + $subject->count() + 1));
                        $event->sheet->getDelegate()->mergeCells('L'.(5 + $subject->count() + 1).':P'.(5 + $subject->count() + 1));
                        $event->sheet->getDelegate()->mergeCells('Q'.(5 + $subject->count() + 1).':Q'.(5 + $subject->count() + 2));

                        $event->sheet->getDelegate()->mergeCells('A'.(4 + $subject->count() + 1).':Q'.(4 + $subject->count() + 1));
                    }

                    $count += (2 + $this->getSubject($item->training_program_id)->count() + 1);
                }

                $event->sheet->getDelegate()->getStyle('A1:Q'.(1 + $count))->applyFromArray([
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
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

            },
        ];
    }

    protected function getSubject($training_program){
        return TrainingRoadmap::query()
            ->from('el_trainingroadmap as a')
            ->leftJoin('el_subject as b', 'b.id', '=', 'a.subject_id')
            ->where('a.training_program_id', '=', $training_program)
            ->get();
    }

    protected function getTeacher($teacher_id){
        return TrainingTeacher::find($teacher_id);
    }

    protected function getResult($user_id, $subject_id){
        return ManagerCourseComplete::query()
            ->where('user_id', '=', $user_id)
            ->where('subject_id', '=', $subject_id)
            ->first();
    }
}
