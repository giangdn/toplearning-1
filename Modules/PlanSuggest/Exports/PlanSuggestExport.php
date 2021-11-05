<?php


namespace Modules\PlanSuggest\Exports;

use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\KPI;
use App\Profile;
use App\Certificate;
use Modules\Capabilities\Entities\CapabilitiesGroupPercent;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Potential\Entities\Potential;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PlanSuggestExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{

    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function __construct($month, $year, $unit, $status)
    {
        $this->month = $month;
        $this->year = $year;
        $this->unit = $unit;
        $this->status = $status;
    }

    public function map($plan_suggest): array
    {
        $this->index++;
        $arr_title = array_values(json_decode($plan_suggest->title,true));
        $titles = Titles::whereIn('id', $arr_title)->pluck('name')->toArray();

        return [
            $this->index,
            $plan_suggest->unit_name,
            $plan_suggest->subject_name,
            implode('; ', $titles),
            $plan_suggest->amount,
            ($plan_suggest->type == 1 ? '{{trans("backend.internal")}}' : '{{trans("backend.outside")}}'),
            $plan_suggest->training_form,
            get_date($plan_suggest->start_date, 'd/m/Y') . ' => ' . get_date($plan_suggest->end_date, 'd/m/Y'),
            $plan_suggest->address,
            $plan_suggest->cost,
            $plan_suggest->note,
            $plan_suggest->status == 1 ? 'Chờ duyệt' : ($plan_suggest->status == 2 ? 'Đã duyệt' : 'Từ chối'),
        ];

    }

    public function query()
    {
        $query =\DB::query()
            ->select([
                'a.*',
                'b.name as unit_name',
                'c.name as training_form'
            ])
            ->from('el_plan_suggest as a')
            ->leftJoin('el_unit as b','a.unit_code','=','b.code')
            ->leftJoin('el_training_form as c', 'c.id', '=', 'a.training_form')
            ->orderBy('a.id', 'ASC');

        if ($this->month)
            $query->where(\DB::raw('month(start_date)'),'=',$this->month);
        if ($this->year)
            $query->where(\DB::raw('year(start_date)'),'=',$this->year);
        if ($this->unit)
            $query->where('a.unit_code','=',$this->unit);
        if ($this->status)
            $query->where('a.status','=',$this->status);

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Đề xuất kế hoạch đào tạo'],
            [
                'STT',
                'Đơn vị',
                'Nội dung đào tạo',
                'Đối tượng',
                'Số lượng',
                'Hình thức',
                'Loại hình',
                'Thời gian',
                'Địa điểm',
                'Chi phí',
                'Ghi chú',
                'Trạng thái'
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
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:L'.(2 + $this->count).'')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                      'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }
}
