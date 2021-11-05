<?php
namespace App\Exports;

use App\User;
use App\ProfileView;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Certificate;

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

class UserExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($search, $unit, $area, $title, $status) {
        $this->search = $search;
        $this->unit = $unit;
        $this->area = $area;
        $this->title = $title;
        $this->status = $status;
    }

    public function map($profile): array
    {
        $this->index++;
        $status = '';
        switch($profile->status_id){
            case 0: $status = 'Nghỉ việc'; break;
            case 1: $status = 'Đang làm'; break;
            case 2: $status = 'Thử việc'; break;
            case 3: $status = 'Tạm hoãn'; break;
        }
        $gender = '';
        switch($profile->gender){
            case 0: $gender = 'Nữ'; break;
            case 1: $gender = 'Nam'; break;
        }

        switch($profile->marriage){
            case 0: $marriage = 'Độc thân'; break;
            case 1: $marriage = 'Kết hôn'; break;
        }

        $parent_units = Unit::getTreeParentUnit($profile->unit_code);
        $levels_unit = Unit::getMaxUnitLevel();

        for ($i=1; $i < $levels_unit; $i++) { 
            ${'unit_code_'.$i} = '';
            ${'unit_name_'.$i} = '';
        }

        foreach($parent_units as $key => $parent_unit) {
            ${'unit_code_'.$key} = $parent_unit->code;
            ${'unit_name_'.$key} = $parent_unit->name;
        }

        $cert = Certificate::where('certificate_code',$profile->certificate_code)->first();

        return [
            $this->index,
            $profile->username,
            $profile->code,
            $profile->lastname,
            $profile->firstname,
            $profile->email,
            $profile->title_code,
            $profile->title_name,
            $unit_code_1 ? $unit_code_1 : '',
            $unit_name_1 ? $unit_name_1 : '',
            $unit_code_2 ? $unit_code_2 : '',
            $unit_name_2 ? $unit_name_2 : '',
            $unit_code_3 ? $unit_code_3 : '',
            $unit_name_3 ? $unit_name_3 : '',
            $unit_code_4 ? $unit_code_4 : '',
            $unit_name_4 ? $unit_name_4 : '',
            $gender,
            $marriage,
            $profile->phone,
            get_date($profile->dob),
            $profile->identity_card,
            get_date($profile->date_range),
            $profile->issued_by,
            $profile->expbank,
            $cert ? $cert->certificate_name : '',
            $profile->address,
            get_date($profile->join_company),
            get_date($profile->date_off),
            $status,
            $profile->id_code,
        ];
    }

    public function query()
    {
        $query = ProfileView::query();
        $query->select([
            'el_profile_view.*',
            'u.username',
            'p.certificate_code',
        ]);
        $query->from('el_profile_view');
        $query->leftjoin('user as u','u.id','=','el_profile_view.user_id');
        $query->leftjoin('el_profile as p','p.user_id','=','el_profile_view.user_id');
        $query->where('el_profile_view.user_id', '>', 2);
        $query->orderBy('el_profile_view.id', 'ASC');

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('el_profile_view.full_name', 'like', '%' . $search . '%');
                $sub_query->orWhere('el_profile_view.code', 'like', '%'. $search .'%');
                $sub_query->orWhere('el_profile_view.email', 'like', '%'. $search .'%');
                $sub_query->orWhere('u.username', 'like', '%'. $search .'%');
            });
        }
        if ($this->area) {
            $query->leftJoin('el_unit AS c', 'c.code', '=', 'el_profile_view.unit_code');
            $query->leftJoin('el_area AS area', 'area.id', '=', 'c.area_id');
            $area = Area::find($this->area);
            $area_id = Area::getArrayChild($area->code);
            $query->where(function ($sub_query) use ($area, $area_id) {
                $sub_query->whereIn('area.id', $area_id);
                $sub_query->orWhere('area.id', '=', $area->id);
            });
        }
        if (!is_null($this->status)) {
            $query->where('el_profile_view.status_id', '=', $this->status);
        }

        if ($this->unit) {
            $unit = Unit::whereIn('id', explode(';', $this->unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('el_profile_view.unit_id', $unit_id);
                $sub_query->orWhere('el_profile_view.unit_id', '=', $unit->id);
            });
        }

        if ($this->title) {
            $title = Titles::where('id', '=', $this->title)->first();
            $query->where('el_profile_view.title_code', '=', $title->code);
        }
        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Danh sách nhân viên'],
            [
                'STT',
                'username',
                'Mã nhân viên',
                'Họ nhân viên',
                'Tên nhân viên',
                'Email',
                'Mã chức danh',
                'Tên chức danh',
                'Mã ĐV 1',
                'ĐV 1',
                'Mã ĐV 2',
                'ĐV 2',
                'Mã ĐV 3',
                'ĐV 3',
                'Mã ĐV 4',
                'ĐV 4',
                'Giới tính',
                'Tình trạng hôn nhân',
                'Số điện thoại',
                'Ngày sinh',
                'Số CMND',
                'Ngày cấp',
                'Nơi cấp',
                'Thâm niên trong nghề',
                'Trình độ',
                'Địa chỉ',
                'Ngày vào làm',
                'Ngày nghỉ việc',
                'Trạng thái',
                'Mã giới thiệu',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:AD1');

                $event->sheet->getDelegate()->getStyle('A1:AD1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'     => true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:AD'.(2 + $this->count).'')->applyFromArray([
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
