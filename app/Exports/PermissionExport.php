<?php
namespace App\Exports;

use App\User;
use App\Profile;
use App\Role;
use App\PermissionType;
use App\RolePermissionType;


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

class PermissionExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function map($profile): array
    {
        $this->index++;
        return [
            $this->index,
            $profile->name,
            $profile->role_name,
            $profile->permission_type_group,
        ];
    }

    public function query()
    {
        $query = Role::query();
        $query->select([
            'a.*',
            'c.description AS role_name', 
            'e.description AS permission_type_group', 
        ]);
        $query->from('el_roles AS a');
        $query->Join('el_role_has_permissions AS b', 'b.role_id', '=', 'a.id');
        $query->Join('el_permissions AS c', 'c.id', '=', 'b.permission_id');
        $query->leftJoin('el_role_permission_type AS d', 'd.permission_id', '=', 'c.id');
        $query->leftJoin('el_permission_type AS e', 'e.id', '=', 'd.permission_type_id');
        $query->orderBy('a.id', 'ASC');
        $query->orderBy('b.permission_id', 'ASC');
        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Vai trò phân quyền'],
            [
                'STT',
                'Tên vai trò',
                'Tên quyền',
                'Nhóm quyền',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:D1');

                $event->sheet->getDelegate()->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()
                ->getStyle('A1:D'.(2 + $this->count).'')
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
