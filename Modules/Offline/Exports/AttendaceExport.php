<?php
namespace Modules\Offline\Exports;

use Modules\Offline\Entities\OfflineCourse;
use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Discipline;
use Modules\Offline\Entities\OfflineSchedule;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AttendaceExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($course_id, $schedule)
    {
        $this->course_id = $course_id;
        $this->schedule = $schedule;
    }

    public function map($result): array
    {
        $this->index++;

        $absent = Absent::where('id', '=', $result->absent_id)->first();
        $absent_reason = AbsentReason::where('id', '=', $result->absent_reason_id)->first();
        $discipline = Discipline::where('id', '=', $result->discipline_id)->first();
        $count = OfflineSchedule::where('id','<=', $this->schedule)->where('course_id',$this->course_id)->count();
        $schedule_time = get_date($result->start_time, 'H:i:s') . ' => ' . get_date($result->end_time, 'H:i:s');

        return [
            $this->index,
            strval($result->profile_code),
            $result->full_name,
            $result->email,
            $result->title_name,
            $result->unit_name,
            $result->parent_unit_name,
            date("d-m-Y", strtotime($result->lesson_date)),
            $schedule_time,
            $result->percent ? $result->percent : '',
            '',
            !empty($discipline) ? $discipline->name : '',
            !empty($absent) ? $absent->name : '',
            !empty($absent_reason) ? $absent_reason->name : '',
        ];
    }

    public function query(){
        $query = OfflineSchedule::query();
        $query->select([
            'b.*',
            'c.code as profile_code',
            'c.full_name',
            'c.email',
            'c.title_name',
            'c.unit_name',
            'c.parent_unit_name',
            'e.absent_id',
            'e.absent_reason_id',
            'e.discipline_id',
            'e.percent',
        ]);
        $query->from('el_offline_schedule AS b');
        $query->Join('el_offline_register AS d', 'd.course_id', '=', 'b.course_id');
        $query->leftJoin('el_profile_view AS c', 'c.user_id', '=', 'd.user_id');
        $query->leftJoin('el_offline_attendance as e', function ($sub){
            $sub->on('e.register_id', '=', 'd.id');
            $sub->on('e.schedule_id', '=', 'b.id');
        });
        $query->where('b.course_id', '=', $this->course_id);
        //$query->orderBy('c.user_id', 'ASC');
        $query->orderBy('d.id', 'ASC');
        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $course = OfflineCourse::find($this->course_id);
        return [
            ['Danh sách điểm danh khóa ' . $course->name],
            [
                'STT',
                'Mã nhân viên',
                'Họ và tên',
                'Email',
                'Chức danh',
                'Đơn vị công tác',
                'Đơn vị quản lý',
                'Ngày học',
                'Buổi học (giờ bắt đầu => giờ kết thúc)',
                'Tham gia (%)',
                'Ghi chú',
                'Vi phạm',
                'Loại nghỉ',
                'Lý do vắng',
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->mergeCells('A1:N1');
                $event->sheet->getDelegate()->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:N'.(2 + $this->count).'')->applyFromArray([
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

                $discipline_arr = Discipline::whereStatus(1)->pluck('name')->toArray();
                $absent_arr = Absent::whereStatus(1)->pluck('name')->toArray();
                $absent_reason_arr = AbsentReason::whereStatus(1)->pluck('name')->toArray();

                for($i = 3; $i <= (2 + $this->count); $i++){
                    $discipline_str = implode(', ', $discipline_arr);
                    $absent_str = implode(', ', $absent_arr);
                    $absent_reason_str = implode(', ', $absent_reason_arr);

                    $discipline = $event->sheet->getDelegate()->getCell('L'.$i)->getDataValidation();
                    $discipline->setType(DataValidation::TYPE_LIST);
                    $discipline->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $discipline->setAllowBlank(false);
                    $discipline->setShowInputMessage(true);
                    $discipline->setShowErrorMessage(true);
                    $discipline->setShowDropDown(true);
                    $discipline->setFormula1('"' . $discipline_str . '"');

                    $absent = $event->sheet->getDelegate()->getCell('M'.$i)->getDataValidation();
                    $absent->setType(DataValidation::TYPE_LIST);
                    $absent->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $absent->setAllowBlank(false);
                    $absent->setShowInputMessage(true);
                    $absent->setShowErrorMessage(true);
                    $absent->setShowDropDown(true);
                    $absent->setFormula1('"' . $absent_str . '"');

                    $absent_reason = $event->sheet->getDelegate()->getCell('N'.$i)->getDataValidation();
                    $absent_reason->setType(DataValidation::TYPE_LIST);
                    $absent_reason->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $absent_reason->setAllowBlank(false);
                    $absent_reason->setShowInputMessage(true);
                    $absent_reason->setShowErrorMessage(true);
                    $absent_reason->setShowDropDown(true);
                    $absent_reason->setFormula1('"' . $absent_reason_str . '"');
                }
            },
        ];
    }
}
