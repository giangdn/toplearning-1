<?php
namespace App\Exports;

use App\User;
use App\Profile;
use App\Models\Categories\Unit;
use Modules\SubjectRegister\Entities\SubjectRegister;

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

class SubjectRegisterExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($search, $unit){
        $this->search = $search;   
        $this->unit = $unit;  
    }

    public function map($profile): array
    {
        $this->index++;
        $status = '';
        switch($profile->status){
            case 0: $status = 'Tắt'; break;
            case 1: $status = 'Bật'; break;
        }
        return [
            $this->index,
            $profile->subject,
            $profile->code,
            $profile->user_code,
            $profile->full_name,
            $profile->title_name,
            $profile->unit_name,
            $profile->parent_unit_name,
            $profile->created_date = get_date($profile->created_at,'d/m/Y H:i:s'),
            $status
        ];
    }

    public function query()
    {
        $query = SubjectRegister::query();
        $query->select('el_subject_register.*','b.full_name','b.code as user_code','b.title_name','b.unit_name','b.parent_unit_name','c.name as subject','c.code');
        $query->from('el_subject_register')
        ->join('el_profile_view as b','el_subject_register.user_id','b.user_id')
        ->join('el_subject as c','el_subject_register.subject_id','c.id');

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('c.name', 'like', '%' . $search . '%');
                $sub_query->orWhere('c.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('b.full_name', 'like', '%'. $search .'%');
            });
        }

        if($this->unit) {
            $units = Unit::whereIn('id', explode(';', $this->unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($units->code);

            $query->where(function ($sub_query) use ($unit_id, $units) {
                $sub_query->orWhereIn('b.unit_id', $unit_id);
                $sub_query->orWhere('b.unit_id', '=', $units->id);
            });
        }

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Danh sách chuyên đề đăng ký'],
            [
                'STT',
                'Mã Kháo học',
                'Tên khóa học',
                'Mã nhân viên',
                'Tên nhân viên',
                'Chức danh',
                'Đơn vị trực tiếp',
                'Đơn vị quan lý',
                'Ngày tạo',
                'Trạng thái',
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
