<?php
namespace App\Exports;

use App\Languages;
use App\LanguagesGroups;

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

class LanguagesExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function map($profile): array
    {
        $this->index++;
        return [
            $this->index,
            $profile->pkey,
            $profile->content,
            $profile->content_en,
            $profile->group_name,
        ];
    }

    public function query()
    {
        $query = Languages::query();
        $query->select([
            'a.*',
            'b.name AS group_name',
        ]);
        $query->from('el_languages AS a');
        $query->leftJoin('el_languages_groups AS b', 'b.id', '=', 'a.groups_id');
        $query->orderBy('a.id', 'DESC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Ngôn ngữ'],
            [
                'STT',
                'Từ khóa',
                'Tiếng Việt',
                'Tiếng Anh',
                'Nhóm',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:E1');

                $event->sheet->getDelegate()->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:E1')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                ]);

            },

        ];
    }

}
