<?php
namespace App\Exports;

use App\Models\Categories\TrainingForm;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Online\Entities\OnlineCourseCost;
use PhpOffice\PhpSpreadsheet\Style\Border;

class IHRPTemplate2Sheet1 implements FromQuery, WithMapping ,WithHeadings, ShouldAutoSize, WithEvents, WithTitle
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $header = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'size'      =>  12,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->getStyle('A4:H4')->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('33C9FF');

                $event->sheet->getDelegate()->getStyle('A4:H'.(4 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('B4')->applyFromArray([
                    'font' => [
                        'color' => ['argb' => 'FF3333'],
                    ],
                ])->getFill()->getStartColor()->setARGB('FFEC33');

                $event->sheet->getDelegate()->getStyle('A3')->applyFromArray([
                    'font' => [
                        'color' => ['argb' => 'FF3333'],
                    ],
                ]);
            },

        ];
    }

    public function headings(): array
    {
        return [
            ['TR_TBLTRAININGCOURSEEMP', '','LS_tblTrainingForm', '', 'LS_tblTrainingField'],
            ['', '', 'LSTrainingFormCode', '', 'LSTrainingFieldCode'],
            ['LSTrainingCourseID', 'TrainingCourseCode', 'LSTrainingFormID', 'GuideBy', 'LSTrainingFieldID', 'TrainingPlace', 'CoSoDanhGia', 'TotalCost'],
            ['STT', 'Mã (*)(!)', 'Hình thức đào tạo (combo)', 'Hướng dẫn bởi', 'Lĩnh vực đào tạo', 'Nơi đào tạo', 'Cơ sở đánh giá', 'Tổng cộng'],
        ];
    }

    public function title(): string
    {
        return 'Template';
    }

    public function query()
    {
        $start_date = date_convert($this->start_date);
        $end_date = date_convert($this->end_date, '23:59:59');

        $query = \DB::query();
        $query->from('el_course_view');
        $query->where('status', '=', 1);
        if ($start_date && $end_date) {
            $query->where('start_date', '>=', $start_date);
            $query->where('start_date', '<=', $end_date);
        }
        $query->get(['id', 'course_type', 'code']);
        $query->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function map($row): array
    {
        if ($row->course_type == 2){
            $teachers = $this->getTeacher($row->id);
            $off = OfflineCourse::find($row->id);
            $training_form = TrainingForm::find($off->training_form_id);

            $register_id = OfflineRegister::where('course_id', '=', $row->id)->pluck('id')->toArray();
            $student_cost = OfflineStudentCost::whereIn('register_id', $register_id)->sum('cost');
            $course_cost = OfflineCourseCost::where('course_id', '=', $row->id)->sum('actual_amount');

            $off_cost = $course_cost + $student_cost;

        }else{
            $onl_cost = OnlineCourseCost::where('course_id', '=', $row->id)->sum('actual_amount');
        }
        $this->index++;

        $obj[] = $this->index;
        $obj[] = $row->code;
        $obj[] = $row->course_type == 2 ? $training_form->name : '';
        $obj[] = $row->course_type == 2 ? implode(", ",$teachers) : '';
        $obj[] = '';
        $obj[] = '0';
        $obj[] = '';
        $obj[] = $row->course_type == 2 ? if_empty(number_format($off_cost, 0), '0') : if_empty(number_format($onl_cost, 0), '0');

        return $obj;
    }

    public function getTeacher($course_id){
        $teacher = OfflineSchedule::leftJoin('el_training_teacher AS b', 'b.id', '=', 'teacher_main_id')
            ->where('course_id', '=', $course_id)
            ->where('b.status', '=', 1)
            ->pluck('b.name')->toArray();

        return $teacher;
    }
}
