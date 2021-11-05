<?php
namespace Modules\TrainingRoadmap\Exports;

use App\Models\Categories\Titles;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithDrawings;
use App\Config;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportRoadmap implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents , WithStartRow
{
    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function __construct() {

    }

    public function map($row): array
    {
        $training_forms = json_decode($row->training_form);
        $training_form = (in_array(1,$training_forms) ? '1,' : '') . (in_array(2,$training_forms) ? '2' : '');
        $this->index++;

        return [
            $this->index,
            $row->title_code,
            $row->title_name,
            $row->subject_code,
            $row->subject_name,
            $training_form,
            $row->completion_time ? $row->completion_time : '',
            $row->order ? $row->order : '',
            $row->content ? $row->content : '',
        ];
    }

    public function query()
    {
        $query = TrainingRoadmap::query();
        $query->select([
            'a.*' , 
            'b.code AS subject_code',
            'b.name AS subject_name',
            'c.code as title_code',
            'c.name as title_name'
        ]);
        $query->from('el_trainingroadmap AS a');
        $query->leftJoin('el_subject AS b', 'b.id', '=', 'a.subject_id');
        $query->leftJoin('el_titles AS c', 'c.id', '=', 'a.title_id');
        $query->orderBy('c.id', 'DESC');
        $query->orderBy('a.order', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            [
                'STT',
                'Mã chức danh',
                'Tên chức danh',
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                'Hình thức (1:Online, 2:Tập trung)',
                'Thời gian bắt buộc hoàn thiện khóa học',
                'Thứ tự',
                'Mô tả',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $header = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],

                    ],
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $title = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()
                    ->getStyle("A1:I1")
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('D')->applyFromArray($title);
                $event->sheet->getDelegate()->getStyle('F')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A1:I'.(1 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],

                        ],
                        'font' => [
                            'name' => 'Arial',
                            'size' =>  12,
                        ],
                    ]);
            },

        ];
    }

    public function startRow(): int
    {
        return 2;
    }

    // public function drawings()
    // {
    //     $storage = \Storage::disk('upload');
    //     if ($storage->exists(Config::getConfig('logo'))) {
    //         $path = $storage->path(Config::getConfig('logo'));
    //     }else{
    //         $path = './images/image_default.jpg';
    //     }

    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('This is my logo');
    //     $drawing->setPath($path);
    //     $drawing->setHeight(100);
    //     $drawing->setCoordinates('A1');

    //     return $drawing;
    // }
}