<?php
namespace App\Exports;

use App\User;
use App\EmulationProgram;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;

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

class EmulationProgramExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function map($profile): array
    {
        $this->index++;
        $status = '';
        $isopen = '';
        switch($profile->status){
            case 0: $status = 'Chưa duyệt'; break;
            case 2: $status = 'Từ chối'; break;
            case 1: $status = 'Duyệt'; break;
        }
        switch($profile->isopen){
            case 0: $isopen = 'Tắt'; break;
            case 1: $isopen = 'Bật'; break;
        }
        return [
            $this->index,
            $profile->code,
            $profile->name,
            $profile->time,
            $profile->description,
            $status,
            $isopen
        ];
    }

    public function query()
    {
        $query = EmulationProgram::query();
        $query->select([
            'a.*',
            \DB::raw('CONCAT(time_start, \' => \', time_end) as time'),
        ]);
        $query->from('el_emulation_program AS a');
        $query->orderBy('a.id', 'ASC');
        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Danh sách Chương trình thi đua'],
            [
                'STT',
                'Mã chương trình',
                'Tên chương trình',
                'Thời gian',
                'Mô tả',
                'Duyệt',
                'Trạng thái',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:G1');

                $event->sheet->getDelegate()->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()
                ->getStyle('A1:G'.(2 + $this->count).'')
                ->applyFromArray([
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
