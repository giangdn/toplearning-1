<?php
namespace Modules\Capabilities\Exports;

use App\Models\Categories\Titles;
use Modules\Capabilities\Entities\Capabilities;
use Modules\Capabilities\Entities\CapabilitiesTitle;

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

class CapabilitiesTitleExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $count = 0;
    protected $index = 0;

    public function __construct($title)
    {
        $this->title = $title;
    }

    public function map($capabilities): array
    {
        $this->index++;
        return [
            $this->index,
            $capabilities->title_code,
            $capabilities->title_name,
            $capabilities->capabilities_code,
            $capabilities->capabilities_name,
            $capabilities->weight . ' %',
            $capabilities->critical_level,
            $capabilities->level,
            $capabilities->goal,

        ];
    }

    public function query()
    {
        $query = CapabilitiesTitle::query();
        $query->select(['a.id', 'a.weight', 'a.critical_level', 'a.level', 'a.goal', 'b.code AS title_code',
        'b.name AS title_name', 'c.code AS capabilities_code', 'c.name AS capabilities_name']);
        $query->from('el_capabilities_title AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_capabilities AS c', 'c.id', '=', 'a.capabilities_id');
        if ($this->title){
            $query->where('a.title_id', '=', $this->title);
        }
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Danh sách khung năng lực theo chức danh'],
            [
                'STT',
                'Mã chức danh',
                'Tên chức danh',
                'Mã năng lực',
                'Tên năng lực',
                'Trọng số (%)',
                'Mức độ quan trọng',
                'Cấp độ',
                'Điểm chuẩn',
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
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor() ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A2:D'.(2 + $this->count).'')->applyFromArray([
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
                $event->sheet->getDelegate()->getStyle('E2:E'.(2 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                ]);
                $event->sheet->getDelegate()->getStyle('F2:I'.(2 + $this->count).'')->applyFromArray([
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