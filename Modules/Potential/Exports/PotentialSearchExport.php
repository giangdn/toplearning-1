<?php


namespace Modules\Potential\Exports;

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
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PotentialSearchExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{

    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function __construct($cert, $from_percent, $to_percent, $from_year, $to_year, $start_date, $end_date)
    {
        $this->cert = $cert;
        $this->from_percent = $from_percent;
        $this->to_percent = $to_percent;
        $this->from_year = $from_year;
        $this->to_year = $to_year;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function map($profile): array
    {
        $this->index++;
        $user_code = $profile->code;
        $d1 = '';
        $d2 = '';
        $d3 = '';
        $ratio = 0;
        $group_percent = '' ;

        if ($profile->sum_practical_goal){

            $ratio = number_format(($profile->sum_practical_goal / $profile->sum_goal) * 100, 0);
            $percent = CapabilitiesGroupPercent::where('from_percent', '<', (float) $ratio)
                ->where('to_percent', '>', (float) $ratio)->first();

            $group_percent = ($percent) ? $percent->percent_group : '';
        }

        $join_company = get_date($profile->join_company, 'Y-m-d');
        $now = date('Y-m-d');

        $max_year = KPI::whereIn('year', function ($subquery) use ($user_code) {
            $subquery->select(\DB::raw('MAX(year)'))
                ->from('el_kpi')
                ->where('user_code', '=', $user_code);
        });
        if ($max_year->exists()) {
            $max_year = $max_year->first()->year;

            $year1 = KPI::getKpi($user_code, $max_year);
            if ($year1) {
                $year2 = KPI::getKpi($user_code, $max_year - 1);
                if ($year1->quarter_4) {
                    $d1 = 'Quý 4/' . $year1->year .' - '. $year1->quarter_4;
                    $d2 = 'Quý 3/' . $year1->year .' - '. $year1->quarter_3;
                    $d3 = 'Quý 2/' . $year1->year .' - '. $year1->quarter_2;
                } else if ($year1->quarter_3) {
                    $d1 = 'Quý 3/' . $year1->year .' - '. $year1->quarter_3;
                    $d2 = 'Quý 2/' . $year1->year .' - '. $year1->quarter_2;
                    $d3 = 'Quý 1/' . $year1->year .' - '. $year1->quarter_1;
                } else if ($year1->quarter_2) {
                    $d1 = 'Quý 2/' . $year1->year .' - '. $year1->quarter_2;
                    $d2 = 'Quý 1/' . $year1->year .' - '. $year1->quarter_1;
                    $d3 = 'Quý 4/' . $year2->year .' - '. $year2->quarter_4;
                } else if ($year1->quarter_1) {
                    $d1 = 'Quý 1/' . $year1->year .' - '. $year1->quarter_1;
                    $d2 = 'Quý 4/' . $year2->year . ' - ' . $year2->quarter_4;
                    $d3 = 'Quý 3/' . $year2->year . ' - ' . $year2->quarter_3;
                }
            }
        }

        return [
            $this->index,
            $profile->code,
            $profile->lastname . ' ' . $profile->firstname,
            $profile->title_name,
            $profile->unit_name,
            $ratio ? $ratio . ' %' : '',
            $group_percent,
            $profile->certificate_name,
            $profile->expbank,
            $d1,
            $d2,
            $d3,
        ];
    }

    public function query()
    {
        $query = Profile::query();
        $query->select([
            'a.*',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.certificate_name',
            'f.sum_practical_goal',
            'f.sum_goal'
        ]);
        $query->from('el_profile AS a');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'a.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'a.unit_code');
        $query->leftJoin('el_cert AS e', 'e.certificate_code', '=', 'a.certificate_code');
        $query->leftJoin('el_capabilities_review AS f', function ($subquery) {
            $subquery->on('f.user_id', '=', 'a.user_id')
                ->whereIn('f.id', function ($subquery2) {
                    $subquery2->select(['id'])
                        ->from('el_capabilities_review')
                        ->whereColumn('user_id', '=', 'f.user_id')
                        ->orderBy('id', 'desc')
                        ->limit(1);
                });
        });
        $query->where('a.user_id', '>', 2);
        if ($this->cert) {
            $query->whereIn('e.id', explode(',', $this->cert));
        }

        if ($this->to_percent) {
            $query->where(\DB::raw('ISNULL((sum_practical_goal / sum_goal) * 100, 0)'), '<=', (float)$this->to_percent);
        }

        if ($this->from_percent) {
            $query->where(\DB::raw('ISNULL((sum_practical_goal / sum_goal) * 100, 0)'), '>=', (float)$this->from_percent);
        }

        if ($this->from_year) {
            $query->where(\DB::raw('CONVERT(float, expbank)'), '>=', (float)$this->from_year);
        }

        if ($this->to_year) {
            $query->where(\DB::raw('CONVERT(float, expbank)'), '<=', (float)$this->to_year);
        }

        if ($this->start_date) {
            $query->where(\DB::raw('ROUND(CONVERT(float, DATEDIFF(day, join_company, GETDATE()))/365, 2)'), '>=', floatval($this->start_date));
        }

        if ($this->end_date) {
            $query->where(\DB::raw('ROUND(CONVERT(float, DATEDIFF(day, join_company, GETDATE()))/365, 2)'), '<=', floatval($this->end_date));
        }
        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Tìm kiếm nhân sự tiềm năng'],
            [
                'STT',
                'Mã nhân viên',
                'Tên nhân viên',
                'Chức danh',
                'Đơn vị',
                'Tỷ lệ đánh giá (%)',
                'Nhóm',
                'Trình độ',
                'Thâm niên trong nghề (Năm)',
                'Đợt 1',
                'Đợt 2',
                'Đợt 3',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:M1');
                $event->sheet->getDelegate()->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:M'.(2 + $this->count).'')->applyFromArray([
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
