<?php
namespace Modules\Report\Export;
use App\Config;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Report\Entities\BC02;
use Modules\Report\Entities\BC03;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC03Export implements WithHeadings, FromCollection, ShouldAutoSize, WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    private $fromDate;
    private $toDate;
    public function __construct($param)
    {
        $this->fromDate = $param->fromDate;
        $this->toDate = $param->toDate;
    }

    public function collection()
    {
        $data = BC03::getData($this->fromDate, $this->toDate);
        $this->index += count($data);
        return new Collection(
            [
                $data
            ]
        );
    }

    public function headings(): array
    {
        return [
            [],
            [],
            [],
            [],
            ['DANH SÁCH KHÓA ĐÀO TẠO CÓ CHI PHÍ'],
            ['Từ '.$this->fromDate.' - '.$this->toDate],
            [
                'STT',
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                'Hình thức',
                'Thời lượng',
                'Từ ngày',
                'Đến ngày',
                'Loại hình đào tạo',
                'Đơn vị đào tạo',
                'Chi phí',
                'Giảng viên',
                'Tổng số học viên',
                'Tham gia',
                '',
                'Không tham gia',
                '',
                'Hoàn thành',
                '',
                'Không hoàn thành',
                '',
                'Đối tượng tham gia',
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '(SL)',
                '(%)',
                '(SL)',
                '(%)',
                '(SL)',
                '(%)',
                '(SL)',
                '(%)',
                '',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $header = [
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->getStyle("A7:U8")->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()
                    ->mergeCells('M7:N7')
                    ->mergeCells('O7:P7')
                    ->mergeCells('Q7:R7')
                    ->mergeCells('S7:T7')
                    ->mergeCells('A7:A8')
                    ->mergeCells('B7:B8')
                    ->mergeCells('C7:C8')
                    ->mergeCells('D7:D8')
                    ->mergeCells('E7:E8')
                    ->mergeCells('F7:F8')
                    ->mergeCells('G7:G8')
                    ->mergeCells('H7:H8')
                    ->mergeCells('I7:I8')
                    ->mergeCells('J7:J8')
                    ->mergeCells('K7:K8')
                    ->mergeCells('L7:L8')
                    ->mergeCells('U7:U8');

                $event->sheet->getDelegate()->getColumnDimension('M')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(10);

                $event->sheet->getDelegate()->getColumnDimension('N')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(10);

                $event->sheet->getDelegate()->getColumnDimension('O')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(10);

                $event->sheet->getDelegate()->getColumnDimension('P')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(10);

                $event->sheet->getDelegate()->getColumnDimension('Q')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(10);

                $event->sheet->getDelegate()->getColumnDimension('R')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(10);

                $event->sheet->getDelegate()->getColumnDimension('S')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(10);

                $event->sheet->getDelegate()->getColumnDimension('T')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(10);

                $event->sheet->getDelegate()->mergeCells('A5:U5')->getStyle('A5')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A6:U6')->getStyle('A6')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle("A7:U".(8 + $this->index))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],

                    ],
                ]);
            },
        ];
    }

    public function startRow(): int
    {
        return 9;
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
