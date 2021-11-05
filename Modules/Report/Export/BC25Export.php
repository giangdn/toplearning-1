<?php
namespace Modules\Report\Export;

use App\Config;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Report\Entities\BC25;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC25Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $total = 0;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->unit_id = $param->unit_id;
    }

    public function query()
    {
        $query = BC25::sql($this->unit_id, $this->from_date, $this->to_date)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($report): array
    {
        $this->index++;
        $quantity = OfflineRegister::where('course_id', '=', $report->id)
            ->where('status', '=', 1)
            ->count();

        $this->total += $quantity;

        $unit_name = Unit::find($this->unit_id)->name;
        return [
            $this->index,
            $unit_name,
            $report->subject_name,
            $quantity ? $quantity : '0',
            get_date($report->start_date, 'd/m/Y') . ' => ' . get_date($report->end_date, 'd/m/Y'),
        ];
    }
    public function headings(): array
    {
        $unit = Unit::find($this->unit_id);
        return [
            [],
            [],
            [],
            [],
            [],
            ['TỔNG HỢP BÁO CÁO ĐÀO TẠO NỘI BỘ'],
            ['Từ: ' . $this->from_date .' - '. $this->to_date],
            ['Đơn vị: '. $unit->name],
            [],
            [
                'STT',
                'Đơn vị',
                'Chuyên đề',
                'Số lượng',
                'Ngày tổ chức',
            ]
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'bold'      =>  true,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()->mergeCells('A6:E6')->getStyle('A6')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A7:E7')->getStyle('A7')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A8:E8')->getStyle('A8')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A10:E10')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->setCellValue('B'.(10 + $this->count + 1), 'Tổng cộng');
                $event->sheet->getDelegate()->setCellValue('D'.(10 + $this->count + 1), $this->total);

                $event->sheet->getDelegate()->getStyle('A10:E'.(10 + $this->count + 1))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },

        ];
    }
    public function startRow(): int
    {
        return 11;
    }

    public function drawings()
    {
        $storage = \Storage::disk('upload');
        if ($storage->exists(Config::getConfig('logo'))) {
            $path = $storage->path(Config::getConfig('logo'));
        }else{
            $path = './images/image_default.jpg';
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath($path);
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}
