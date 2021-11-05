<?php
namespace Modules\Report\Export;

use App\Permission;
use App\Profile;
use App\Models\Categories\Unit;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Report\Entities\BC35;

class BC35Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow
{
    use Exportable, RegistersEventListeners;
    private $index;
    private $from_date;
    private $to_date;
    private $unit;
    
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->unit = $param->unit;
    }
    
    public function query()
    {
        $query = BC35::getQuery();
    
        if ($this->from_date) {
            $from_date = date_convert($this->from_date, '00:00:00');
            $query->where('course.start_date', '>=', $from_date);
        }
    
        if ($this->to_date) {
            $to_date = date_convert($this->to_date, '23:59:59');
            $query->where('course.start_date', '<=', $to_date);
        }
    
        if ($this->unit) {
            $query->inUnit($this->unit);
        }
    
        if (!Permission::isAdmin()) {
            $managers = Permission::getIdUnitManagerByUser('module.training_unit');
            if ($managers) {
                $query->whereIn('unit.id', $managers);
            }
        }
        
        $query->orderBy(\DB::raw('CONCAT ( lastname, \' \', firstname )'), 'ASC');
        
        return $query;
    }
    
    public function map($report): array
    {
        $this->index++;
        $updated = Profile::where('user_id', '=', $report->updated_by)->first();
        if ($updated) {
            $report->updated = $updated->lastname . ' '. $updated->firstname . ' ('. $updated->code .')';
        }
    
        switch ($report->status) {
            case 0: $report->status = 'Nghỉ việc';break;
            case 1: $report->status = 'Đang làm';break;
            case 2: $report->status = 'Thử việc';break;
            case 3: $report->status = 'Tạm hoãn';break;
        }
        $report->created = get_date($report->created_at);
    
        if ($report->unit_level == 2) {
            $report->level2 = $report->unit_name;
        }
    
        if ($report->unit_level == 3) {
            $report->level2 = Unit::firstOrNew(['code' => $report->unit_parent])->name;
            $report->level3 = $report->unit_name;
        }
    
        if ($report->unit_level == 4) {
            $level3 = Unit::firstOrNew(['code' => $report->unit_parent]);
            $report->level2 = Unit::firstOrNew(['code' => $level3->parent_code])->name;
            $report->level3 = $level3->name;
            $report->level4 = $report->unit_name;
        }
        
        return [
            $this->index,
            $report->code,
            $report->fullname,
            $report->level2,
            $report->level3,
            $report->level4,
            $report->join_company,
            $report->title_name,
            $report->level,
            $report->subject_name,
            $report->date_complete,
            $report->score,
            $report->teacher_code,
            $report->teacher_name,
            $report->course_child_name,
            $report->status,
            $report->updated,
            $report->created,
        ];
    }
    
    public function headings(): array
    {
        return [
            ['BÁO CÁO KẾT QUẢ HỌC TẬP'],
            ['Từ '.get_date($this->from_date).' - '.get_date($this->to_date)],
            [],
            [
                'STT',
                trans('backend.employee_code'),
                trans('backend.fullname'),
                'Nhà hàng',
                'Phòng Ban/Nhãn hàng',
                'Bộ phận/Tên cửa hàng',
                'Ngày gia nhập',
                'Vị trí',
                'Cấp bậc',
                'Môn',
                'Ngày hoàn thành',
                '% hoàn thành',
                'Giảng viên',
                'Tên giảng viên',
                'Loại hoàn thành',
                'Tình trạng',
                'Người cập nhật kết quả',
                'Ngày tạo',
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
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    
                    ],
                    'font' => [
                        'size'      =>  12,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()
                    ->getStyle("A".($this->startRow()-1).":R".($this->startRow()-1))
                    ->applyFromArray($header)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DDDDDD');
                $event->sheet->getDelegate()->mergeCells('A1:R1')->getStyle('A1')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A2:R2')->getStyle('A2')->applyFromArray($title);
            },
        ];
    }
    
    public function startRow(): int
    {
        return 5;
    }
}