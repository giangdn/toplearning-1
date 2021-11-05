<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Titles;
use App\Scopes\CompanyScope;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\ReportNew\Entities\BC22;
use Modules\ReportNew\Entities\BC23;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use Modules\User\Entities\UserCompletedSubject;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC23Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $column = 1;
    public function __construct($param)
    {
        $this->title_id = $param->title_id;
    }

    public function query()
    {
        $query = BC23::sql($this->title_id);
        return $query;
    }
    public function map($row): array
    {
        $training_roadmap = TrainingRoadmap::whereTitleId($this->title_id)->orderBy('id')->get();

        $obj = [];
        $this->index++;
        $obj[] = $this->index;
        $obj[]=$row->name;
        $obj[]=$row->employees;

        foreach ($training_roadmap as $index => $roadmap) {
            $user_complete = UserCompletedSubject::query()
                ->from('el_user_completed_subject as a')
                ->leftJoin('el_profile as b', 'b.user_id', '=', 'a.user_id')
                ->where('b.title_id', '=', $this->title_id)
                ->where('a.subject_id', '=', $roadmap->subject_id)
                ->count();

            $obj[] = $user_complete;
            $obj[] = number_format(($user_complete/($row->employees > 0 ? $row->employees : 1))*100, 2);
        }
        return $obj;
    }

    public function headings(): array
    {

        $title= Titles::find($this->title_id)->name;
        $training_roadmap = TrainingRoadmap::whereTitleId($this->title_id)->orderBy('id')->get();
        $colHeader1= [
            'STT',
            'Chức danh',
            'Số lượng CBNV',
            'Số lượng CBNV hoàn thành'

        ];
        $colHeader= [
            '',
            '',
            '',

        ];
        $this->column=3;
        foreach ($training_roadmap as $index => $roadmap) {
            array_push($colHeader,$roadmap->subject->name);
            array_push($colHeader,'Tỷ lệ %');
            $this->column+=2;
        }
        return [
            [],
            [],
            [],
            [],
            [],
            ['Thống kê tỷ lệ hoàn thành tháp đào tạo theo chức danh'],
            [$title],
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
                $event->sheet->getDelegate()->mergeCells('A6:F6')
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
                $event->sheet->getDelegate()->mergeCells('D8:'.$columnName->getColumnIndex().'8');
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
