<?php
namespace App\Exports;

use App\User;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Modules\Libraries\Entities\Libraries;
use Modules\Libraries\Entities\LibrariesCategory;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Quiz\Entities\QuizUserSecondary;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportLibraries implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($type){
        $this->type = $type;
    }

    public function map($row): array
    {
        $this->index++;
        switch($row->type){
            case 1: $type = 'Sách'; break;
            case 2: $type = 'Ebook'; break;
            case 3: $type = 'Tài liệu'; break;
            case 4: $type = 'Video'; break;
            case 5: $type = 'Sách nói'; break;
        }

        return [
            $this->index,
            $row->name,
            $row->name_author,
            $row->cate_name,
            $type,
            $row->lastname. ' ' . $row->firstname,
        ];
    }

    public function query()
    {
        $query = Libraries::query();
        $query->select(['a.*','b.name as cate_name','c.lastname','c.firstname']);
        $query->from('el_libraries as a');
        $query->leftJoin('el_libraries_category as b','b.id','=','a.category_id');
        $query->leftJoin('el_profile as c','a.updated_by','=','c.user_id');
        $query->where('a.type', $this->type);
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        switch($this->type){
            case 1: $type = 'Sách'; break;
            case 2: $type = 'Ebook'; break;
            case 3: $type = 'Tài liệu'; break;
            case 4: $type = 'Video'; break;
            case 5: $type = 'Sách nói'; break;
        }
        return [
            ['Danh sách '.$type],
            [
                'STT',
                'Tên '.$type,
                'Tên tác giả',
                'Danh mục '.$type,
                'Thể loại',
                'Cập nhật bởi',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:F1');

                $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()
                ->getStyle('A1:F'.(2 + $this->count).'')
                ->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'name'      => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

            },

        ];
    }

}
