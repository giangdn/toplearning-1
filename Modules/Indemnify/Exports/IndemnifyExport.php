<?php


namespace Modules\Indemnify\Exports;
use App\Models\Categories\Titles;
use App\Profile;

use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Indemnify\Entities\TotalIndemnify;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class IndemnifyExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($unit, $title)
    {
        $this->unit = $unit;
        $this->title = $title;
    }

    public function map($profile): array
    {
        $this->index++;
        $total_indem = TotalIndemnify::where('user_id', '=', $profile->user_id)->first();

        return [
            $this->index,
            $profile->code,
            $profile->lastname . ' ' . $profile->firstname,
            $profile->title_name,
            $profile->unit_name,
            '',
            '',
            '',
            $total_indem ? number_format($total_indem->total_cost, 0, ',', '.') : '',
            '',
            $total_indem ? $total_indem->compensated == 1 ? 'Đã bồi hoàn' : 'Chưa bồi hoàn' : '',
        ];
    }

    public function query()
    {
        Indemnify::addGlobalScope(new DraftScope());
        $student_indemnify = Indemnify::join('el_offline_course','el_indemnify.course_id','=','el_offline_course.id')
            ->whereNotNull('el_indemnify.commit_date')
            ->select('user_id')
            ->groupBy('user_id');

        $query = \DB::table('el_profile as a')
            ->joinSub($student_indemnify,'b',function ($join){
                $join->on('a.user_id','=','b.user_id');
            })
            ->leftJoin('el_unit as c','a.unit_code','=','c.code')
            ->leftJoin('el_unit as e','e.code','=','c.parent_code')
            ->leftJoin('el_titles as d','a.title_code','=','d.code')
            ->select([
                "a.user_id",
                "a.code",
                "a.firstname",
                "a.lastname",
                "c.name AS unit_name",
                "d.name AS title_name",
                "e.name AS parent_name"
            ]);
        $query->orderBy('a.id', 'ASC');
        if ($this->unit) {
            $unit = Unit::where('id', '=', $this->unit)->first();
            $query->where('a.unit_code', '=', $unit->code);
        }

        if ($this->title) {
            $title = Titles::where('id', '=', $this->title)->first();
            $query->where('a.title_code', '=', $title->code);
        }

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Theo dõi bồi hoàn'],
            [
                'STT',
                'Mã nhân viên',
                'Tên nhân viên',
                'Chức danh',
                'Đơn vị',
                'Ngày nhận HS',
                'Ngày xử lý',
                'Số tiền theo QĐ',
                'Số tiền thực tế',
                'Hình thức trả',
                'Trạng thái bồi hoàn',
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
