<?php
namespace Modules\Report\Export;

use App\Config;
use App\Models\Categories\Unit;
use App\Profile;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Report\Entities\BC41;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use PhpOffice\PhpSpreadsheet\Chart\Axis;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\GridLines;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Properties;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpParser\Node\Stmt\Label;

class BC41Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count_char = 7;

    public function __construct($param)
    {
        $this->title_id = $param->title_id;
    }

    public function query()
    {
        $query = BC41::sql($this->title_id)->orderBy('a.user_id', 'ASC');
        return $query;
    }

    public function map($report): array
    {
        $this->index++;
        $user_code = Profile::usercode($report->user_id);
        $user_name = Profile::fullname($report->user_id);

        $profile = Profile::find($report->user_id);
        $arr_unit = Unit::getTreeParentUnit($profile->unit_code);
        $unit_name = $arr_unit ? $arr_unit[$profile->unit->level]->name : '';
        $parent_unit_name = $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '';
        $unit_name_level2 = $arr_unit ? $arr_unit[2]->name : '';

        $result = [];
        $result[] = $this->index;
        $result[] = $user_code;
        $result[] = $user_name;
        $result[] = $report->title_name;
        $result[] = $unit_name;
        $result[] = $parent_unit_name;
        $result[] =  $unit_name_level2;

        $training_roadmap = TrainingRoadmap::where('title_id', '=', $this->title_id)->get();
        foreach ($training_roadmap as $item){
            if ($subject = $item->subject){
                if ($subject->isCompleted($report->user_id)){
                    $result[] = 'X';
                }else{
                    $result[] = '';
                }
            }
        }

        return $result;
    }

    public function headings(): array
    {
        $training_roadmap = TrainingRoadmap::where('title_id', '=', $this->title_id)->get();
        $head = [];

        $head[] = 'STT';
        $head[] = 'Mã nhân viên';
        $head[] = 'Họ tên';
        $head[] = 'Chức danh';
        $head[] = 'Đơn vị trực tiếp';
        $head[] = 'Đơn vị gián tiếp cấp 1';
        $head[] = 'Công ty';
        foreach ($training_roadmap as $item){
            if ($item->subject){
                $head[] = $item->subject->name;

                $this->count_char += 1;
            }
        }

        return [
            [],
            [],
            [],
            [],
            [],
            [],
            ['THỐNG KÊ KẾT QUẢ THÁP ĐÀO TẠO THEO CHỨC DANH'],
            [' '],
            $head
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size' =>  12,
                        'bold' =>  true,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->mergeCells('A7:G7')->getStyle('A7')->applyFromArray($title);

                $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                if ($this->count_char > 26){
                    $num = floor($this->count_char/26);
                    $num_1 = $this->count_char - ($num * 26);

                    $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
                }else{
                    $char = $arr_char[($this->count_char - 1)];
                }

                $event->sheet->getDelegate()->getStyle('A9:'.$char.'9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A9:'.$char.(9 + $this->index))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::HORIZONTAL_CENTER,
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
