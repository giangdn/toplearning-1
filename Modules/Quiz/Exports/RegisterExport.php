<?php
namespace Modules\Quiz\Exports;

use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizRegister;

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

class RegisterExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $total_percent = 0;

    public function __construct($quiz_id)
    {
        $this->quiz_id = $quiz_id;
    }

    public function map($register): array
    {
        $this->index++;
        return [
            $this->index,
            $register->user_code,
            $register->lastname . ' ' .  $register->firstname,
            $register->title_name,
            $register->unit_name,
            $register->parent_unit_name,
            $register->dob ? date("d-m-Y", strtotime($register->dob)) : '',
            $register->identity_card,
            $register->email,
            $register->part_name,
            get_date($register->part_start_date, 'H:i d/m/Y'),
            get_date($register->part_end_date, 'H:i d/m/Y'),
        ];
    }

    public function query(){
        $query = QuizRegister::query();
        $query->select([
            'a.*',
            'b.lastname as lastname',
            'b.firstname as firstname',
            'b.code as user_code',
            'b.dob',
            'b.identity_card',
            'b.email',
            'b.parent_unit_name',
            'c.name as title_name',
            'd.name as unit_name',
            'e.name as part_name',
            'e.start_date as part_start_date',
            'e.end_date as part_end_date',
        ]);
        $query->from('el_quiz_register AS a');
        $query->leftJoin('el_profile_view AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_quiz_part AS e', 'e.id', '=', 'a.part_id');
        $query->where('a.type', '=', 1);
        $query->where('a.quiz_id', '=', $this->quiz_id);
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $quiz = Quiz::find($this->quiz_id);
        return [
            ['Thí sinh nội bộ đăng kí thi '. $quiz->name],
            [
                'STT',
                'Mã nhân viên',
                'Họ và tên',
                'Chức danh',
                'Đơn vị',
                'Đơn vị quản lý',
                'Ngày sinh',
                'CMND',
                'Email',
                'Ca thi',
                'Thời gian bắt đầu',
                'Thời gian kết thúc',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:L1');
                $event->sheet->getDelegate()->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:L'.(2 + $this->count).'')->applyFromArray([
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
}