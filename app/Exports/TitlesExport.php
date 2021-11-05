<?php
namespace App\Exports;

use App\User;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class TitlesExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function map($profile): array
    {
        $this->index++;
        $status = '';
        switch($profile->status){
            case 0: $status = 'Tắt'; break;
            case 1: $status = 'Bật'; break;
        }

        return [
            $this->index,
            $profile->code,
            $profile->name,
            $profile->title_rank_name,
            $profile->unit_type_name,
            $status,
        ];
    }

    public function query()
    {
        $query = Titles::query();
        $query->select([
            'el_titles.id',
            'el_titles.code',
            'el_titles.name',
            'el_titles.status',
            'tr.name as title_rank_name',
            'ut.name as unit_type_name',
        ]);
        $query->from('el_titles');
        $query->leftJoin('el_title_rank AS tr', 'tr.id', '=', 'el_titles.group');
        $query->leftJoin('el_unit_type AS ut', 'ut.id', '=', 'el_titles.unit_type');

        $query->orderBy('el_titles.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Chức danh'],
            [
                'STT',
                'Mã chức danh',
                'Tên chức danh',
                'Nhóm chức danh',
                'Loại đơn vị',
                'Trạng thái',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:F1');

                $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:F'.(2 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

            },

        ];
    }

}
