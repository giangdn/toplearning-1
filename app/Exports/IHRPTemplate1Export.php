<?php
namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class IHRPTemplate1Export implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

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
        $query->from('el_course_view');
        if ($start_date && $end_date) {
            $query->where('start_date', '>=', $start_date);
            $query->where('start_date', '<=', $end_date);
        }
        $query->orderBy('id', 'ASC');

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
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->getStyle('A4:F4')->applyFromArray($header)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('33C9FF');

                $event->sheet->getDelegate()->getStyle('A4:F'.(4 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
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
            ['LS_TBLTRAININGCOURSE'],
            [],
            ['LSTrainingCourseID', 'LSTrainingCourseCode', 'VNName', 'FromDate', 'ToDate', 'Used'],
            ['STT', 'Mã (*)(!)', 'Tên khóa', 'Từ ngày', 'Đến ngày', 'Sử dụng']
        ];
    }

    public function map($row): array
    {
        $this->index++;

        return[
            $this->index,
            $row->code,
            $row->name,
            get_date($row->start_date, 'd/m/Y'),
            get_date($row->end_date, 'd/m/Y'),
            1
        ];
    }
}
