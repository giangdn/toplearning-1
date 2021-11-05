<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\Area;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\ReportNew\Entities\BC21;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC21Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $column = 1;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->area_id = $param->area_id;
        $this->title_id = $param->title_id;
        $this->unit_id = $param->unit_id;
        $this->training_type_id = $param->training_type_id;
    }

    public function query()
    {
        $query = BC21::sql()->orderBy('name', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $obj = [];
        $this->index++;
        $obj[] = $this->index;
        $obj[]=$row->code;
        $obj[]=$row->name;
        $obj[]=get_date($row->start_date);
        $obj[]=get_date($row->end_date);
        $obj[]=$row->created_user;
        return $obj;
    }

    public function headings(): array
    {
        $colHeader= [
            'STT',
            trans('lacourse.course_code'),
            trans('lacourse.course_name'),
            'Từ ngày',
            'Đến ngày',
            trans('lageneral.creator')
        ];
        return [
            [],
            [],
            [],
            [],
            [],
            ['DANH SÁCH CÁC KHÓA HỌC TRỰC TUYẾN ĐANG MỞ'],
            [],
            [],
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

                $event->sheet->getDelegate()->mergeCells('A6:F6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A9:F9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A9:F9')
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
        return 9;
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
