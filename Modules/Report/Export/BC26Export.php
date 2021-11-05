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
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Report\Entities\BC25;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Report\Entities\BC26;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC26Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $total = 0;
    public function __construct($param)
    {
        $this->year = $param->year;
    }

    public function query()
    {
        $query = BC26::sql($this->year)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($report): array
    {
        $this->index++;
        $total = OfflineCourse::where('unit_id', '=', $report->unit_id)
            ->where('subject_id', '=', $report->subject_id)->count();

        $quarter_1 = OfflineCourse::where('unit_id', '=', $report->unit_id)
            ->where('subject_id', '=', $report->subject_id)
            ->whereNotNull('unit_id')
            ->where(\DB::raw('year(start_date)'), '=', $this->year)
            ->where(function($sub){
                $sub->orWhere(\DB::raw('month(start_date)'), '=', 10)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 11)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 12);
            })->count();

        $quarter_2 = OfflineCourse::where('unit_id', '=', $report->unit_id)
            ->where('subject_id', '=', $report->subject_id)
            ->whereNotNull('unit_id')
            ->where(\DB::raw('year(start_date)'), '=', ($this->year + 1))
            ->where(function($sub){
                $sub->orWhere(\DB::raw('month(start_date)'), '=', 1)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 2)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 3);
            })->count();

        $quarter_3 = OfflineCourse::where('unit_id', '=', $report->unit_id)
            ->where('subject_id', '=', $report->subject_id)
            ->whereNotNull('unit_id')
            ->where(\DB::raw('year(start_date)'), '=', ($this->year + 1))
            ->where(function($sub){
                $sub->orWhere(\DB::raw('month(start_date)'), '=', 4)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 5)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 6);
            })->count();

        $quarter_4 = OfflineCourse::where('unit_id', '=', $report->unit_id)
            ->where('subject_id', '=', $report->subject_id)
            ->whereNotNull('unit_id')
            ->where(\DB::raw('year(start_date)'), '=', ($this->year + 1))
            ->where(function($sub){
                $sub->orWhere(\DB::raw('month(start_date)'), '=', 7)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 8)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 9);
            })->count();

        return [
            $this->index,
            $report->unit_name,
            $total,
            $quarter_1,
            $quarter_2,
            $quarter_3,
            $quarter_4,
            $report->subject_name,
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
            ['BÁO CÁO CHI TIẾT THỰC HIỆN ĐÀO TẠO NỘI BỘ'],
            ['NĐTC: ' . $this->year .' - '. ($this->year + 1)],
            [' '],
            [
                'STT',
                'Đơn vị',
                'Tổng',
                'Số lượng chuyên đề đào tạo',
                '',
                '',
                '',
                'Nhóm chuyên đề đào tạo',
            ],
            [
                '','','','Quý 1', 'Quý 2', 'Quý 3', 'Quý 4', ''
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
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()->mergeCells('A6:H6')->getStyle('A6')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A7:H7')->getStyle('A7')->applyFromArray($title);

                $event->sheet->getDelegate()->mergeCells('A9:A10');
                $event->sheet->getDelegate()->mergeCells('B9:B10');
                $event->sheet->getDelegate()->mergeCells('C9:C10');
                $event->sheet->getDelegate()->mergeCells('H9:H10');
                $event->sheet->getDelegate()->mergeCells('D9:G9');

                $event->sheet->getDelegate()->getStyle('A9:H'.(10 + $this->count).'')->applyFromArray([
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
