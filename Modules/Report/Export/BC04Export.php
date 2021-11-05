<?php
namespace Modules\Report\Export;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Report\Entities\BC04;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class BC04Export implements  WithHeadings, FromCollection, ShouldAutoSize, WithEvents, WithStartRow
{
    use Exportable;
    protected $index = 0;
    private $fromDate;
    private $toDate;
    private $training_from;
    public function __construct($param)
    {
        $this->fromDate=$param->fromDate;
        $this->toDate=$param->toDate;
        $this->training_from=$param->training_from;
    }
    public function collection()
    {
        $data = BC04::getData($this->fromDate,$this->toDate,$this->training_from);
        $this->index += count($data);
        return new Collection(
            [
                $data
            ]
        );
    }
    public function headings(): array
    {
        $training_from = BC04::getTrainingFrom($this->training_from);
        $training_from = $training_from?$training_from->name:'';

        return [['DANH SÁCH KHÓA ĐÀO TẠO TỔ CHỨC '.strtoupper($training_from)],['Từ '.get_date($this->fromDate). ' - '.get_date($this->toDate)],
            [
                'STT',
                'Khóa học',
                'Hình thức',
                'Thời lượng',
                'Từ ngày',
                'Đến ngày',
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
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->getStyle("A3:S4")->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');

                $event->sheet->getDelegate()
                    ->mergeCells('K3:L3')
                    ->mergeCells('M3:N3')
                    ->mergeCells('O3:P3')
                    ->mergeCells('Q3:R3')
                    ->mergeCells('A3:A4')
                    ->mergeCells('B3:B4')
                    ->mergeCells('C3:C4')
                    ->mergeCells('D3:D4')
                    ->mergeCells('E3:E4')
                    ->mergeCells('F3:F4')
                    ->mergeCells('G3:G4')
                    ->mergeCells('H3:H4')
                    ->mergeCells('I3:I4')
                    ->mergeCells('J3:J4')
                    ->mergeCells('S3:S4');

                $event->sheet->getDelegate()->getColumnDimension('K')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(8);

                $event->sheet->getDelegate()->getColumnDimension('L')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(8);

                $event->sheet->getDelegate()->getColumnDimension('M')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(8);

                $event->sheet->getDelegate()->getColumnDimension('N')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(8);

                $event->sheet->getDelegate()->getColumnDimension('O')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(8);

                $event->sheet->getDelegate()->getColumnDimension('P')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(8);

                $event->sheet->getDelegate()->getColumnDimension('Q')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(10);

                $event->sheet->getDelegate()->getColumnDimension('R')->setAutoSize(false);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(10);

                $event->sheet->getDelegate()->mergeCells('A1:S1')->getStyle('A1')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A2:S2')->getStyle('A2')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle("A3:S".(4 + $this->index))->applyFromArray([
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
        return 5;
    }
}