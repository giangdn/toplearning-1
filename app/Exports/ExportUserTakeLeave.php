<?php
namespace App\Exports;

use App\User;
use App\ProfileView;
use Modules\User\Entities\ProfileTakeLeave;

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

class ExportUserTakeLeave implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function map($row): array
    {
        $this->index++;

        if (!empty($row->absent_code)){
            $reason = $row->el_absent_name;
        }else{
            $reason = $row->absent_name;
        }

        return [
            $this->index,
            $row->user_code,
            $row->full_name,
            $row->email,
            $row->unit_name,
            $row->parent_unit_name,
            $row->title_name,
            $row->position_name,
            $reason,
            date('d-m-Y',strtotime($row->start_date)) . ' => ' . date('d-m-Y',strtotime($row->end_date)),
        ];
    }

    public function query()
    {
        $query = ProfileView::query();
        $query->select([
            'a.*',
            'b.code as user_code', 
            'b.full_name', 
            'b.email', 
            'b.unit_name', 
            'b.parent_unit_name', 
            'b.title_name', 
            'b.position_name', 
            'c.name as el_absent_name'
        ]);
        $query->from('el_profile_take_leave as a');
        $query->leftJoin('el_profile_view as b','b.user_id','=','a.user_id');
        $query->leftJoin('el_absent as c','a.absent_code','=','c.code');
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        
        return [
            ['Danh sách Nhân viên nghỉ phép'],
            [
                'STT',
                'Mã nhân viên',
                'Tên nhân viên',
                'Email',
                'Đơn vị công tác',
                'Đơn vị quản lý',
                'Chức danh',
                'Chức vụ',
                'Lý do',
                'Ngày',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:J1');

                $event->sheet->getDelegate()->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()
                ->getStyle('A1:J'.(2 + $this->count).'')
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
