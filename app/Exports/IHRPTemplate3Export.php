<?php
namespace App\Exports;

use App\Profile;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineResult;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class IHRPTemplate3Export implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function query()
    {
        $start_date = date_convert($this->start_date);
        $end_date = date_convert($this->end_date, '23:59:59');

        $query = \DB::query();
        $query->from('el_course_register_view as a');
        $query->leftJoin('el_course_view as b', function ($sub){
            $sub->on('b.course_id', '=', 'a.course_id')->whereColumn('b.course_type', '=', 'a.course_type');
        });
        $query->where('a.status', '=', 1);
        $query->where('b.status', '=', 1);

        if ($start_date && $end_date) {
            $query->where('b.start_date', '>=', $start_date);
            $query->where('b.start_date', '<=', $end_date);
        }
        $query->select(['user_id', 'course_id', 'a.course_type']);
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
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

                $event->sheet->getDelegate()->getStyle('A4:G4')->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('33C9FF');

                $event->sheet->getDelegate()->getStyle('A4:G'.(4 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('B4:C4')->applyFromArray([
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
            ['TR_TBLTRAININGCOURSEEMP', 'TR_tblTrainingCourse', 'HR_tblEmp'],
            ['', 'TrainingCourseCode', 'EmpCode'],
            ['ColAuTo', 'TrainingCourseID', 'EmpID', 'IsPass', 'IsCert', 'IsReport', 'Note'],
            ['STT', 'Mã khóa học', 'Mã nhân viên', 'Đạt', 'Chứng chỉ', 'Báo cáo', 'Ghi chú'],
        ];
    }

    public function map($row): array
    {
        $this->index++;

        if ($row->course_type == 1){
            $course_onl = OnlineCourse::find($row->course_id);
            $user_onl = Profile::find($row->user_id);
            $result = OnlineResult::get();
            if (!empty($result)){
                $result_onl = OnlineResult::where('user_id', '=', $row->user_id)
                    ->where('course_id', '=', $row->course_id)->get(['result']);
            }
        }else{
            $course_off = OfflineCourse::find($row->course_id);
            $user_off = Profile::find($row->user_id);
            $result = OfflineResult::get();
            if (!empty($result)) {
                $result_off = OfflineResult::where('user_id', '=', $row->user_id)
                    ->where('course_id', '=', $row->course_id)->get(['result']);
            }
        }

        return [
            $this->index,
            $row->course_type == 1 ? $course_onl->code : $course_off->code,
            $row->course_type == 1 ? $user_onl->code : $user_off->code,
            $row->course_type == 1 ? (isset($result_onl) ? ($result_onl == '1' ? '1' : '0') : '') : (isset($result_off) ? ($result_off == '1' ? '1' : '0') : ''),
            '1',
            '0',
            '',
        ];
    }
}
