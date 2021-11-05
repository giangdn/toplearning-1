<?php
namespace App\Exports;

use App\User;
use App\Profile;
use App\DonatePoints;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Models\Categories\UnitManager;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Categories\Area;

class UnitExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $level;

    public function __construct($level){
        $this->level = $level;    
    }

    public function map($profile): array
    {
        $this->index++;

        if($this->level !== '0') {
            $unit_manager = UnitManager::query()
            ->select([
                \DB::raw('CONCAT(user_code ,\' - \', lastname, \' \', firstname) as fullname')
            ])
            ->from('el_unit_manager as a')
            ->leftJoin('el_profile as b', 'b.code', '=', 'a.user_code')
            ->where('a.unit_code', '=', $profile->code)
            ->pluck('fullname')->toArray();

            $parent_unit =  $profile->parent_name;
        } else {
            $unit_manager = UnitManager::query()
            ->select(['a.user_code'])
            ->from('el_unit_manager as a')
            ->where('a.unit_code', '=', $profile->code)
            ->pluck('a.user_code')->toArray();

            $parent_unit =  $profile->parent_code;
        }

        $manager = implode(',', $unit_manager);

        $area = Area::getParentArea2($profile->area_code);
        $area_2_code = !empty($area[2]) ? $area[2] : '';
        $area_3_code = !empty($area[3]) ? $area[3] : '';

        return [
            $this->index,
            $profile->code,
            $profile->name,
            $parent_unit,
            $profile->unit_type_name,
            $manager,
            $area_2_code ? $area_2_code : '',
            $area_3_code ? $area_3_code : '',
        ];
    }

    public function query()
    {
        $query = Unit::query();
        $query->select([
            'a.*',
            'b.name AS unit_type_name',
            'c.name AS parent_name',
            'c.code AS parent_code',
            'd.code as area_code',
        ]);
        $query->from('el_unit AS a');
        $query->leftJoin('el_unit_type AS b', 'b.id', '=', 'a.type');
        $query->leftJoin('el_unit AS c', 'c.code', '=', 'a.parent_code');
        $query->leftJoin('el_area AS d', 'd.id', '=', 'a.area_id');
        if($this->level !== '0') {
            $query->where('a.level', '=', $this->level);
        }
        $query->orderBy('a.id', 'ASC');
        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        if($this->level !== '0') {
            $name = 'Đơn vị';
            $parent_code = ''; 
            $unit_manager_code = ''; 
        } else {
            $name = 'Tất cả Đơn vị';
            $parent_code = '(Mã Đơn vị quản lý)'; 
            $unit_manager_code = '(Mã Người quản lý)'; 
        }
        return [
            [$name],
            [
                'STT',
                'Mã đơn vị',
                'Đơn vị',
                'Đơn vị quản lý '. $parent_code,
                'Loại đơn vị',
                'Người quản lý '. $unit_manager_code,
                'Mã Miền',
                'Mã Khu vực',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:H1');

                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()
                ->getStyle('A1:H'.(2 + $this->count).'')
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
