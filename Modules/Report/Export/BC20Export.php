<?php
namespace Modules\Report\Export;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Capabilities\Entities\CapabilitiesGroupPercent;
use Modules\Report\Entities\BC20;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\Theme;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class BC20Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
    }

    public function query()
    {
        $query = BC20::sql($this->from_date, $this->to_date)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($report): array
    {
//        if (empty($report->parent_name)){
//            $parent = $report->unit_name;
//            $unit = '';
//        }else{
//            $parent = $report->parent_name;
//            $unit = $report->unit_name;
//        }

        if ($report->training_form == 1){
            $training_form = 'Tự học';
        }
        if ($report->training_form == 2){
            $training_form = 'TĐV kèm cặp';
        }
        if ($report->training_form == 3){
            $training_form = 'Nội bộ';
        }
        if ($report->training_form == 4){
            $training_form = 'Thuê ngoài';
        }

        $this->index++;
        return [
            $this->index,
            $report->user_code,
            $report->lastname . ' ' . $report->firstname,
            $report->title_name,
            $report->unit_name,
            $report->subject_code,
            $report->subject_name,
            $report->capabilities_name,
            $report->priority_level,
            $report->training_time,
            $training_form
        ];
    }
    public function headings(): array
    {
        return [
            ['Báo cáo Tổng hợp nhu cầu đào tạo'],
            ['Từ: ' . $this->from_date .' - '. $this->to_date],
            [
                'STT',
                'MSNV',
                'Nhân viên',
                'Chức danh',
                'Đơn vị',
                'Mã học phần',
                'Tên học phần',
                'Năng lực cần bồi dưỡng',
                'Mức độ ưu tiên',
                'Thời gian đào tạo',
                'Hình thức đào tạo'
            ],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()->mergeCells('A1:K1')->getStyle('A1')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A2:K2')->getStyle('A2')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A3:K'.(3 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },

        ];
    }
    public function startRow(): int
    {
        return 2;
    }
}
