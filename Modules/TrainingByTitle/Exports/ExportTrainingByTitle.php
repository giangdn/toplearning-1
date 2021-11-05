<?php
namespace Modules\TrainingByTitle\Exports;

use App\Models\Categories\Titles;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithDrawings;
use App\Config;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use Modules\TrainingByTitle\Entities\TrainingByTitle;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;

class ExportTrainingByTitle implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents , WithStartRow
{
    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function __construct() {

    }

    public function map($row): array
    {
        $this->index++;

        return [
            $this->index,
            $row->title_code,
            $row->title_name,
            $row->name,
            $row->num_date_category,
            $row->subject_code,
            $row->subject_name,
            $row->num_time,
        ];
    }

    public function query()
    {
        $query = TrainingByTitleCategory::query();
        $query->select([
            'a.*' , 
            'b.subject_code',
            'b.subject_name',
            'b.num_time',
            'c.code as title_code',
            'c.name as title_name'
        ]);
        $query->from('el_training_by_title_category AS a');
        $query->leftJoin('el_training_by_title_detail AS b', 'b.training_title_category_id', '=', 'a.id');
        $query->leftJoin('el_titles AS c', 'c.id', '=', 'a.title_id');
        $query->orderBy('c.id', 'DESC');
        $query->orderBy('a.id', 'DESC');

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
                'Danh mục',
                'Thời gian cần hoàn thành',
                trans('lacourse.course_code'),
                trans('lacourse.course_name'),
                'Thời lượng',
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
                    ->getStyle("A1:H1")
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('E')->applyFromArray($title);
                $event->sheet->getDelegate()->getStyle('H')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A1:H'.(1 + $this->index))
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
}