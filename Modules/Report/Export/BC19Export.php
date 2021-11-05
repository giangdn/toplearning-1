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
use Modules\Capabilities\Entities\Capabilities;
use Modules\Capabilities\Entities\CapabilitiesCategory;
use Modules\Capabilities\Entities\CapabilitiesGroupPercent;
use Modules\Capabilities\Entities\CapabilitiesReviewDetail;
use Modules\Report\Entities\BC19;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\Theme;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class BC19Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $char = 'G';
    protected $count_check = [];

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->user_id = $param->user_id;
        $this->title_id = $param->title_id;
    }

    public function query()
    {
        $query = BC19::sql($this->from_date, $this->to_date, $this->user_id, $this->title_id)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($report): array
    {
        $review_detail = CapabilitiesReviewDetail::where('review_id', '=', $report->id)->get();
        $capabilities = Capabilities::orderBy('id', 'desc')->get();
        $this->index++;
        $result = [];

        $percent = number_format(($report->sum_practical_goal / $report->sum_goal)*100, 0);
        $group_percent = CapabilitiesGroupPercent::where('from_percent', '<=', $percent)
            ->where(function ($subquery) use ($percent) {
                $subquery->orWhere('to_percent', '>=', $percent);
                $subquery->orWhereNull('to_percent');
            })->first();

//        if (empty($report->parent_name)){
//            $parent = $report->unit_name;
//            $unit = '';
//        }else{
//            $parent = $report->parent_name;
//            $unit = $report->unit_name;
//        }

        $result[] = $this->index;
        $result[] = $report->user_code;
        $result[] = $report->lastname . ' ' . $report->firstname;
        $result[] = $report->title_name;
        $result[] = $report->unit_name;
        $result[] = $percent;
        $result[] = $group_percent ? $group_percent->percent_group : '';

        foreach ($capabilities as $capability){
            foreach ($review_detail as $detail){
                if ($capability->id == $detail->capabilities_id){
                    $result[] = $detail->practical_level < $detail->standard_level ? 'x' : '';

                    if ($detail->practical_level < $detail->standard_level){
                        $this->count_check[] = 'check_'. $detail->capabilities_id;
                    }
                }
            }
        }
        return $result;
    }
    public function headings(): array
    {
        $title = [];
        $title_1 = [];

        $title[] = 'STT';
        $title[] = 'MSNV';
        $title[] = 'Nhân viên';
        $title[] = 'Chức danh';
        $title[] = 'Đơn vị';
        $title[] = 'Tỷ lệ (%)';
        $title[] = 'Nhóm';

        $title_1[] = '';
        $title_1[] = '';
        $title_1[] = '';
        $title_1[] = '';
        $title_1[] = '';
        $title_1[] = '';
        $title_1[] = '';

        $capa_cate = CapabilitiesCategory::get();
        foreach ($capa_cate as $cate){
            $title[] = $cate->name;
            $capabilities = Capabilities::where('category_id', '=', $cate->id)->orderBy('id', 'desc')->get();
            foreach ($capabilities as $key => $item){
                $title_1[] = $item->name;
                if ($key > 0){
                    $title[] = '';
                }
            }
        }

        return [
            ['Báo cáo Tổng hợp kết quả đánh giá năng lực'],
            ['Từ: ' . $this->from_date .' - '. $this->to_date],
            $title,
            $title_1
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $capabi = Capabilities::get();
                $count = $capabi->count();

                foreach ($capabi as $key => $item){
                    if ($key == 0){
                        $chr = 'H';
                    }

                    $event->sheet->getDelegate()->getColumnDimension($chr)->setAutoSize(false);
                    $event->sheet->getDelegate()->getColumnDimension($chr)->setWidth(13);

                    $chr = chr(ord($chr) + 1);
                }

                $num = ord($this->char) + $count;
                if ($num > 90){
                    $char = 'A';
                    $this->char = $char . chr(ord($char) + ($num - 91));
                }else{
                    $this->char = chr(ord($this->char) + $count);
                }

                $char = 'G';
                $char1 = '';
                $capa_cate = CapabilitiesCategory::get();
                foreach ($capa_cate as $key => $cate){
                    $capabilities = Capabilities::where('category_id', '=', $cate->id)->get();
                    if ($key == 0){
                        $char = chr(ord($char) + $capabilities->count());
                        $event->sheet->getDelegate()->mergeCells('H3:'.$char.'3');
                    }else{
                        $char = chr(ord($char1 ? $char1 : $char) + 1);
                        $char1 = chr(ord($char) + $capabilities->count() - 1);

                        $event->sheet->getDelegate()->mergeCells($char.'3:'.$char1.'3');
                    }
                }

                $title = [
                    'font' => [
                        'size' =>  12,
                        'bold' =>  true,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()->mergeCells('A1:'.$this->char.'1')->getStyle('A1')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A2:'.$this->char.'2')->getStyle('A2')->applyFromArray($title);

                $event->sheet->getDelegate()->mergeCells('A3:A4');
                $event->sheet->getDelegate()->mergeCells('B3:B4');
                $event->sheet->getDelegate()->mergeCells('C3:C4');
                $event->sheet->getDelegate()->mergeCells('D3:D4');
                $event->sheet->getDelegate()->mergeCells('E3:E4');
                $event->sheet->getDelegate()->mergeCells('F3:F4');
                $event->sheet->getDelegate()->mergeCells('G3:G4');

                $event->sheet->getDelegate()->getStyle('A3:'.$this->char.'4')
                    ->getAlignment()
                    ->setWrapText(true);

                $event->sheet->getDelegate()->getRowDimension(3)->setRowHeight(20);
                $event->sheet->getDelegate()->getRowDimension(4)->setRowHeight(100);

                $event->sheet->getDelegate()->getStyle('A3:'.$this->char.''.(4 + $this->count + 1))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'size' =>  11,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                if ($this->index == $this->count && $this->count > 0){
                    $event->sheet->getDelegate()->setCellValue('A'.(4 + $this->count + 1), 'Tổng cộng');
                    $event->sheet->getDelegate()->setCellValue('B'.(4 + $this->count + 1), '');
                    $event->sheet->getDelegate()->setCellValue('C'.(4 + $this->count + 1), '');
                    $event->sheet->getDelegate()->setCellValue('D'.(4 + $this->count + 1), '');
                    $event->sheet->getDelegate()->setCellValue('E'.(4 + $this->count + 1), '');
                    $event->sheet->getDelegate()->setCellValue('F'.(4 + $this->count + 1), '');
                    $event->sheet->getDelegate()->setCellValue('G'.(4 + $this->count + 1), '');

                    $event->sheet->getDelegate()->mergeCells('A'.(4 + $this->count + 1).':G'.(4 + $this->count + 1));

                    $capabilities_last = Capabilities::orderBy('id', 'desc')->get();
                    $count_check = array_count_values($this->count_check);
                    foreach ($capabilities_last as $key1 => $capability){
                        if ($key1 == 0){
                            $char_last = 'H';
                        }
                        foreach ($count_check as $key => $item){
                            $id = preg_replace("/[^0-9]./", '', $key);
                            if ($capability->id == $id)
                            {
                                $event->sheet->getDelegate()->setCellValue($char_last.''.(4 + $this->count + 1), $item);
                            }
                        }
                        $char_last = chr(ord($char_last) + 1);
                    }

                    $event->sheet->getDelegate()->getStyle('A'.(4 + $this->count + 1).':'.$this->char.''.(4 + $this->count + 1))->applyFromArray([
                        'font' => [
                            'size' =>  11,
                            'name' => 'Arial',
                            'bold' =>  true,
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ])->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FCE850');
                }

            },

        ];
    }
    public function startRow(): int
    {
        return 2;
    }
}
