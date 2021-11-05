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

class RegisterSecondaryExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
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
            $register->user_name,
            get_date($register->dob, 'd/m/Y'),
            $register->email,
            $register->identity_card,
            $register->part_name,
            get_date($register->part_start_date, 'H:i d/m/Y'),
            get_date($register->part_end_date, 'H:i d/m/Y'),
        ];
    }

    public function query(){
        $query = QuizRegister::query();
        $query->select([
            'a.*',
            'b.code as user_code',
            'b.name as user_name',
            'b.dob',
            'b.email',
            'b.identity_card',
            'c.name as part_name',
            'c.start_date as part_start_date',
            'c.end_date as part_end_date',
        ]);
        $query->from('el_quiz_register AS a');
        $query->leftJoin('el_quiz_user_secondary AS b', 'b.id', '=', 'a.user_id');
        $query->leftJoin('el_quiz_part AS c', 'c.id', '=', 'a.part_id');
        $query->where('a.type', '=', 2);
        $query->where('a.quiz_id', '=', $this->quiz_id);
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $quiz = Quiz::find($this->quiz_id);
        return [
            ['Thí sinh bên ngoài đăng kí thi '. $quiz->name],
            [
                'STT',
                'Mã nhân viên',
                'Họ và tên',
                'Ngày sinh',
                'Email',
                'CMND',
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
                $event->sheet->getDelegate()->mergeCells('A1:I1');
                $event->sheet->getDelegate()->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:I'.(2 + $this->count).'')->applyFromArray([
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