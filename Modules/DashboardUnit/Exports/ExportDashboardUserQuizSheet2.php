<?php


namespace Modules\DashboardUnit\Exports;

use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Quiz\Entities\QuizPart;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\Categories\TrainingForm;
use App\TypeCost;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Config;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Categories\TrainingType;
use Modules\DashboardUnit\Entities\DashboardUnitByCourse;
use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use Modules\DashboardUnit\Entities\DashboardUnitByQuiz;
use Modules\Quiz\Entities\QuizType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportDashboardUserQuizSheet2 implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithStartRow, WithDrawings, WithTitle
{

    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function __construct($type, $unit_user, $child_arr, $area, $unit_type, $unit, $start_date, $end_date) {
        $this->type = $type;
        $this->unit_user = $unit_user;
        $this->child_arr = $child_arr;
        $this->unit = $unit;
        $this->unit_type = $unit_type;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->area = $area;
    }

    public function map($row): array
    {
        $this->index++;
        $unit_user = $this->unit_user;
        $child_arr = $this->child_arr;
        $year = date('Y');

        $sum_total = 0;
        for ($i = 1; $i <= 12; $i++){
            $i = ($i < 10) ? '0'.$i : $i;

            if($row->quiz_type != 0) {
                $course_by_units = $this->count($i, $unit_user, $child_arr, $year, $row->quiz_type);
                $sum_total += $course_by_units;
            } else if ($row->quiz_type == 0) {
                $sum = [];
                foreach($this->total as  $total) {
                    if (isset($sum[$total[0]])) {
                        $sum[ $total[0] ] += $total[1];
                    } else {
                        $sum[ $total[0] ] = $total[1];
                    }
                }
                foreach($sum as $key => $item) {
                    if($key == $i) {
                        $sum_total += $item;
                    }
                }
            }
        }

        $answer_name = [];
        $answer_name[] = $this->index;
        $answer_name[] = $row->quiz_type_name;
        // $answer_name[] = @$unit_user->name;
        $answer_name[] = $sum_total;
        // dd($row);

        for ($i = 1; $i <= 12; $i++){
            $i = ($i < 10) ? '0'.$i : $i;

            if($row->quiz_type != 0) {
                $course_by_units = $this->count($i, $unit_user, $child_arr, $year, $row->quiz_type);
                $answer_name[] = $course_by_units == 0 ? '0' : $course_by_units;
                $this->total[] = [
                    $i,
                    $course_by_units
                ];
            } else if ($row->quiz_type == 0) {
                $sum = [];
                foreach($this->total as  $total) {
                    if (isset($sum[$total[0]])) {
                        $sum[ $total[0] ] += $total[1];
                    } else {
                        $sum[ $total[0] ] = $total[1];
                    }
                }
                foreach($sum as $key => $item) {
                    if($key == $i) {
                        $answer_name[] = $item == 0 ? '0' : $item;
                    }
                }
            } else {
                $answer_name[] = '0';
            }
        }

        return [
           $answer_name,
        ];
    }

    public function query()
    {
        $unit_user = $this->unit_user;
        $child_arr = $this->child_arr;
        $year = date('Y');

        $query = DashboardUnitByQuiz::query();
        $query->select([
            'a.quiz_type',
            'a.total',
            'a.quiz_type_name',
            \DB::raw('count(*) as sum_total'),
        ]);
        $query->from('el_dashboard_unit_by_quiz AS a');
        $query->whereNotNull('quiz_type');
        $query->where(function ($sub){
            $sub->orWhere('a.quiz_type', '=', 0);
            $sub->orWhereExists(function ($sub2){
                $sub2->select(['id'])
                    ->from('el_quiz_type as quiz_type')
                    ->whereColumn('quiz_type.id', '=', 'a.quiz_type');
            });
        });
        // $query->where(function ($sub) use ($unit_user, $child_arr){
        //     $sub->orWhere('unit_id', '=', @$unit_user->id);
        //     $sub->orWhereIn('unit_id', $child_arr);
        // });
        $query->where('year', '=', $year);
        $query->orderBy('a.total', 'ASC');
        //$query->orderBy('a.id', 'ASC');
        $query->groupBy(['quiz_type','quiz_type_name','total']);

        $this->count = $query->get()->count();
        // dd($query->get());
        return $query;
    }

    public function headings(): array
    {
        $area = Area::find($this->area);
        $export_name = 'Thống kê lượt CBNV thi theo loại kỳ thi';

        $title_arr1[] = 'STT';
        $title_arr1[] = 'Tên Kỳ thi';
        // $title_arr1[] = 'Đơn vị';
        $title_arr1[] = 'Tổng số ca';

        for ($i = 1; $i <= 12; $i++){
            $i = ($i < 10) ? '0'.$i : $i;
            $title_arr1[] = 'Tháng ' . (int) $i;
        }

        return [
            [],
            [],
            [],
            [],
            [],
            [$export_name],
            ['Miền', @$area->name],
            ['Thời gian', $this->start_date . ($this->end_date ? ' đến '. $this->end_date : '')],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            $title_arr1,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $title = [
                    'font' => [
                        'size'      =>  12,
                        'name' => 'Arial',
                        'bold'      =>  true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];


                $event->sheet->getDelegate()->mergeCells('A6:O6')->getStyle('A6')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A23:P23')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A23:P'.(23 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
            },
        ];
    }

    public function startRow(): int
    {
        return 19;
    }

    public function drawings()
    {
        $storage = \Storage::disk('upload');
        if ($storage->exists(Config::getConfig('logo'))) {
            $path = $storage->path(Config::getConfig('logo'));
        }else{
            $path = './images/image_default.jpg';
        }

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath($path);
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function count($i, $unit_user, $child_arr, $year, $quiz_type) {
        $check_quiz_type = QuizType::get(['id', 'name']);
        $prefix = \DB::getTablePrefix();

        if($check_quiz_type->count() > 0) {

            QuizPart::addGlobalScope(new CompanyScope());
            $query = QuizPart::query();
            $query->leftJoin('el_quiz_register as b','b.part_id','=','el_quiz_part.id');
            $query->where('b.type', '=', 1);
            $query->whereIn('el_quiz_part.quiz_id', function ($sub2) use ($quiz_type){
                $sub2->select(['id'])
                    ->from('el_quiz')
                    ->where('status', '=', 1)
                    ->where('is_open', '=', 1)
                    ->where('type_id', '=', $quiz_type)
                    ->pluck('id')->toArray();
            });
            $query->whereExists(function ($sub) use ($prefix, $i) {
                $sub->select(['id'])
                    ->from('el_quiz_result as result')
                    ->whereColumn('result.user_id', '=', 'b.user_id')
                    ->whereColumn('result.quiz_id', '=', 'b.quiz_id')
                    ->where('result.type', '=', 1)
                    ->where(\DB::raw('month('.$prefix.'result.updated_at)'), '=', $i);
            });
            /*$query->where(\DB::raw('month('.$prefix.'a.start_date)'), '<=', $i)
                ->where(function ($sub) use ($prefix, $i){
                    $sub->orWhereNull('a.end_date');
                    $sub->orWhere(\DB::raw('month('.$prefix.'a.end_date)'), '>=', $i);
                });*/

            if ($this->unit || $this->unit_type || $this->area){
                $query->leftJoin('el_profile_view AS c', 'c.user_id', '=', 'b.user_id');
            }

            if ($this->unit){
                $units = Unit::whereIn('id', explode(';', $this->unit))->latest('id')->first();
                $unit_id = Unit::getArrayChild($units->code);

                $query->where(function ($sub_query) use ($unit_id, $units) {
                    $sub_query->orWhereIn('c.unit_id', $unit_id);
                    $sub_query->orWhere('c.unit_id', '=', $units->id);
                });
            }
            if ($this->unit_type){
                $unit_by_type = Unit::whereType($this->unit_type)->pluck('id')->toArray();
                $query->whereIn('c.unit_id', $unit_by_type);
            }
            if ($this->area){
                $areas = Area::whereIn('id', explode(';', $this->area))->latest('id')->first();
                $area_id = Area::getArrayChild($areas->code);

                $query->leftjoin('el_unit as d','d.id','=','c.unit_id');
                $query->where(function ($sub_query) use ($area_id, $areas) {
                    $sub_query->orWhereIn('d.area_id', $area_id);
                    $sub_query->orWhere('d.area_id', '=', $areas->id);
                });
            }
            if ($this->start_date){
                $start_date = date_convert($this->start_date);
                $query->where('el_quiz_part.start_date', '>=', $start_date);
            }
            if ($this->end_date){
                $end_date = date_convert($this->end_date, '23:59:59');
                $query->where('el_quiz_part.end_date', '<=', $end_date);
            }

            $course_by_units = $query->count();
        } else {
            $course_by_units = 0;
        }


        return $course_by_units;
    }

    public function title(): string
    {
        return 'Sheet2';
    }
}
