<?php
namespace App\Exports;

use App\User;
use App\Profile;
use App\DonatePoints;
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

class DonateExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function map($profile): array
    {
        $this->index++;
        return [
            $this->index,
            $profile->code,
            $profile->score,
            $profile->note,
        ];
    }

    public function query()
    {
        $query = Profile::query();
        $query->select([
            'b.*',
            'a.score AS score',
            'a.note AS note',
        ]);
        $query->from('el_donate_points AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->where('b.user_id', '>', 2);
        $query->orderBy('b.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Tặng điểm'],
            [
                'STT',
                'Mã nhân viên',
                'score',
                'note',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:D1');

                $event->sheet->getDelegate()->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:D'.(2 + $this->count).'')->applyFromArray([
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
