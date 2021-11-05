<?php
namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class IHRPTemplate2Sheet2 implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithTitle
{
    use Exportable;
    protected $index = 0;

    public function query()
    {
        $query = \DB::query();
        $query->from('el_training_form');
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
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->getStyle('A1:C1')->applyFromArray($header);

                $event->sheet->getDelegate()->getStyle('A1:C'.(1 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },

        ];
    }

    public function headings(): array
    {
        return [
            'LSTrainingFormCode', 'Name', 'VNName'
        ];
    }

    public function map($row): array
    {
        $this->index++;

        return[
            $this->index,
            $row->name,
            $row->name,
        ];
    }

    public function title(): string
    {
        return 'Hình thức đào tạo';
    }
}