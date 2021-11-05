<?php
namespace Modules\Report\Export;

use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Quiz\Entities\QuizType;
use Modules\Report\Entities\BC32;
use Modules\Report\Entities\BC36;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class BC32Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithStrictNullComparison, WithCharts
{
    use Exportable, RegistersEventListeners;
    private $index;
    private $from_date;
    private $to_date;
    private $training_form;
    private $type;
    private $unit;
    
    public function __construct($param)
    {
        $this->index = 0;
        $this->from_date = date_convert($param->from_date, '00:00:00');
        $this->to_date   = date_convert($param->to_date, '23:59:59');
        $this->training_form = $param->training_form;
        $this->type = $param->type;
        $this->unit = $param->unit;
    }
    
    public function query()
    {
        $query = BC32::getQuery();
        
        if ($this->training_form) {
            $query->whereIn('id', $this->training_form);
        }
        
        return $query;
    }
    
    public function map($row): array
    {
        $this->index ++;
        $row->total = BC32::getTotalObject($this->training_form, $this->type, $this->from_date, $this->to_date, $this->unit);
        $row->join = BC32::getTotalJoin($this->training_form, $this->type, $this->from_date, $this->to_date, $this->unit);
        $row->completed = BC32::getTotalCompleted($this->training_form, $this->type, $this->from_date, $this->to_date, $this->unit);
        $row->not_join = $row->total - $row->join;
        $row->not_join = $row->not_join > 0 ? $row->not_join : 0;
        $row->absent = BC32::getTotalAbsent($this->training_form, $this->type, $this->from_date, $this->to_date, $this->unit);
        
        return [
            $this->index,
            $row->name,
            $row->total,
            $row->join,
            $row->completed,
            $row->not_join,
            $row->absent
        ];
    }
    
    public function headings(): array
    {
        return [
            ['BÁO CÁO TÌNH HÌNH TỔ CHỨC KỲ THI'],
            ['Từ '.get_date($this->from_date).' - '.get_date($this->to_date)],
            [],
            [
                'STT',
                'Hình thức',
                'Tổng',
                'Tham gia',
                'Hoàn thành',
                'Không tham gia',
                'Vắng mặt',
            ],
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $header = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    
                    ],
                    'font' => [
                        'size'      =>  12,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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
                
                $event->sheet->getDelegate()
                    ->getStyle("A".($this->startRow()-1).":I".($this->startRow()-1))
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');
                
                $event->sheet->getDelegate()
                    ->getStyle("A5:I5")
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');
                
                $event->sheet->getDelegate()->mergeCells('A4:A5');
                $event->sheet->getDelegate()->mergeCells('B4:B5');
                $event->sheet->getDelegate()->mergeCells('I4:I5');
                $event->sheet->getDelegate()->mergeCells('C4:D4');
                $event->sheet->getDelegate()->mergeCells('E4:F4');
                $event->sheet->getDelegate()->mergeCells('G4:H4');
                $event->sheet->getDelegate()->mergeCells('A1:I1')->getStyle('A1')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A2:I2')->getStyle('A2')->applyFromArray($title);
            },
        ];
    }
    
    public function startRow(): int
    {
        return 5;
    }
    
    public function charts() {
    
    }
}