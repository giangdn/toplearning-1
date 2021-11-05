<?php
namespace App\Exports;

use App\User;
use App\Profile;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\LevelSubject;

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

class LevelSubjectExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
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
            $profile->training_program_name,
            $status,
        ];
    }

    public function query()
    {
        $query = LevelSubject::query();
        $query->select([
            'a.*',
            'b.name AS training_program_name',
        ]);
        $query->from('el_level_subject AS a');
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'a.training_program_id');
        $query->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Danh sách cấp độ'],
            [
                'STT',
                'Mã cấp độ',
                'Tên cấp độ',
                'Chương trình đào tạo',
                'Trạng thái',
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

                $event->sheet->getDelegate()->getStyle('A1:E'.(2 + $this->count).'')->applyFromArray([
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
