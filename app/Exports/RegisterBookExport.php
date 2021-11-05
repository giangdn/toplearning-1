<?php
namespace App\Exports;

use App\User;
use App\Profile;
use App\Role;
use App\PermissionType;
use App\RolePermissionType;
use Modules\Libraries\Entities\RegisterBook;
use Modules\Libraries\Entities\Libraries;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;

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

class RegisterBookExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function map($profile): array
    {
        $this->index++;
        $approved = '';
        switch($profile->approved){
            case 0: $approved = 'Từ chối'; break;
            case 1: $approved = 'Duyệt'; break;
        }
        $status = '';
        switch($profile->status){
            case 1: $status = 'Chưa lấy sách'; break;
            case 2: $status = 'Đang mượn sách'; break;
            case 3: $status = 'Đã trả sách'; break;
        }

        return [
            $this->index,
            $profile->book_name,
            $profile->quantity,
            $profile->full_name,
            $profile->unit,
            $profile->title,
            $profile->borrow_date,
            $profile->user_return_book,
            $profile->pay_date,
            $profile->register_date,
            $status,
            $approved,
        ];
    }

    public function query()
    {
        $query = RegisterBook::query();
        $query->select([
            'a.*',
            'c.name AS book_name', 
            'd.name AS unit',
            'e.name AS title',
            \DB::raw('CONCAT(lastname, \' \', firstname) as full_name'),
        ]);
        $query->from('el_register_book AS a');
        $query->Join('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->Join('el_libraries AS c', 'c.id', '=', 'a.book_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_titles AS e', 'e.code', '=', 'b.title_code');
        $query->orderBy('a.id', 'ASC');
        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Danh sách mượn sách'],
            [
                'STT',
                'Tên Sách',
                'Số lượng',
                'Người mượn',
                'Đơn vị',
                'Chức danh',
                'Ngày mượn',
                'Ngày trả',
                'Hạn trả',
                'Ngày đăng ký',
                'Trạng thái',
                'Duyệt',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:L1');

                $event->sheet->getDelegate()->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()
                ->getStyle('A1:L'.(2 + $this->count).'')
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
