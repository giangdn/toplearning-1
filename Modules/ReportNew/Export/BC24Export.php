<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\ReportNew\Entities\BC24;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC24Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $column = 1;
    public function __construct($param)
    {
        $this->month = $param->month;
        $this->year = $param->year;
    }

    public function query()
    {
        $query = BC24::sql($this->month,$this->year);
        return $query;
    }
    public function map($row): array
    {
        $obj = [];
        $this->index++;
        $obj[] = $this->index;
        $obj[]=$row->code;
        $obj[]=$row->unit_name;
        $sum_class=0;
        $sum_attend=0;
        $sum_completed=0;
        for ($i=1;$i<=$this->month;$i++) {
            $class = $row->{"class_$i"};
            $attend = $row->{"attend_$i"};
            $completed = $row->{"completed_$i"};
            $obj[] = $class;
            $obj[] = $attend;
            $obj[] = $completed;
            $obj[] = $attend - $completed;
            $sum_class+= (int)$class;
            $sum_attend+= (int)$attend;
            $sum_completed+= (int)$completed;
        }
        $obj[] = $sum_class;
        $obj[] = $sum_attend;
        $obj[] = $sum_completed;
        $obj[] = $sum_attend-$sum_completed;
        return $obj;
    }

    public function headings(): array
    {

        $colHeader1= [
            'STT',
            'Mã',
            'Đơn vị',

        ];
        for ($i=1;$i<=$this->month;$i++){
            array_push($colHeader1,'Tháng '.$i);
            array_push($colHeader1,'');
            array_push($colHeader1,'');
            array_push($colHeader1,'');
        }
        array_push($colHeader1,'Lũy kế từ đầu năm');
        array_push($colHeader1,'');
        array_push($colHeader1,'');
        array_push($colHeader1,'');
        $colHeader= [
            '',
            '',
            '',

        ];
        $this->column=3;
        for ($i=0;$i<= $this->month;$i++) {
            array_push($colHeader,'Số lớp');
            array_push($colHeader,'Số lượt tham dự');
            array_push($colHeader,'Số lượt hoàn thành');
            array_push($colHeader,'Số lượt không hoàn thành');
            $this->column+=4;
        }
        return [
            [],
            [],
            [],
            [],
            [],
            ['TỔNG HỢP TÌNH HÌNH THAM GIA ĐÀO TẠO CÁC KHÓA ELEARNING THEO ĐƠN VỊ - THÁNG '.$this->month.' - '.$this->year],
            [],
            $colHeader1,
            $colHeader
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $columnName = $event->sheet->getDelegate()->getColumnDimensionByColumn($this->column);
                $event->sheet->getDelegate()->mergeCells('A6:G6')
                ->getStyle('A6')
                ->applyFromArray($title);

                // header
                $event->sheet->getDelegate()->getStyle('A8:'.$columnName->getColumnIndex().'9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');
                $event->sheet->getDelegate()->mergeCells('A8:A9');
                $event->sheet->getDelegate()->mergeCells('B8:B9');
                $event->sheet->getDelegate()->mergeCells('C8:C9');
                $colStart = 4;
                for ($i=0;$i<=$this->month;$i++) {

                    $columnMergeFrom = $event->sheet->getDelegate()->getColumnDimensionByColumn($colStart);
                    $columnMergeTo = $event->sheet->getDelegate()->getColumnDimensionByColumn($colStart+3);
                    $event->sheet->getDelegate()->mergeCells($columnMergeFrom->getColumnIndex(). '8:' . $columnMergeTo->getColumnIndex() . '8');
                    $colStart+=4;
                }
                // detail item
                $event->sheet->getDelegate()->getStyle('A8:'.$columnName->getColumnIndex().(9 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ])->getAlignment()->setWrapText(true);
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

        LogoModel::addGlobalScope(new CompanyScope());
        $logo = LogoModel::where('status',1)->first();
        if ($logo) {
            $path = $storage->path($logo->image);
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
