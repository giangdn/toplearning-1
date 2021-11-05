<?php
namespace Modules\Report\Export;

use App\Config;
use App\PlanApp;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Report\Entities\BC18;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\Theme;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC18Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->unit_id = $param->unit_id;
        $this->userCode = $param->userCode;
        $this->userName = $param->userName;
        $this->area_id = $param->area;
        $this->to_date = $param->to_date;
    }

    public function query()
    {
        $query = BC18::sql($this->from_date, $this->to_date)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($report): array
    {
        $this->index++;
        return [
            $this->index,
            $report->user_code,
            $report->user_name,
            $report->number_hits,
            get_date($report->created_at, 'H:i:s d/m/Y'),
            get_date($report->updated_at, 'H:i:s d/m/Y'),

        ];
    }
    public function headings(): array
    {
        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO LẦN TRUY CẬP'],
            ['Từ: ' . $this->from_date .' - '. $this->to_date],
            [],
            ['STT', 'MSNV', 'Tên học viên', 'Lần truy cập', 'Thời gian bắt đầu', 'Lần truy cập cuối'],
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
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()->mergeCells('A6:F6')->getStyle('A6')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A7:F7')->getStyle('A7')->applyFromArray($title);

                $event->sheet->getDelegate()
                    ->getStyle('A9:F9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A9:F'.(9 + $this->count).'')->applyFromArray([
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
        return 10;
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
