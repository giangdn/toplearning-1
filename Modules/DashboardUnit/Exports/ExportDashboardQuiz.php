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

class ExportDashboardQuiz implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithStartRow, WithDrawings, WithCharts
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
        $training_types = TrainingType::where('status',1)->get();

        if($this->type == 0) {
            $export_name = 'Thống kê số ca thi theo loại kỳ thi';
        } else {
            $export_name = 'Thống kê lượt CBNV thi theo loại kỳ thi';
        }

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

                $event->sheet->getDelegate()->addChart($this->lineChart());
                $event->sheet->getDelegate()->addChart($this->pieChart());
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
        $first_month = date("Y-$i-01 00:00:00");
        $d = new \DateTime($first_month);
        $last_month = $d->format('Y-m-t 23:59:59');
        $prefix = \DB::getTablePrefix();

        $check_quiz_type = QuizType::get(['id', 'name']);
        if($check_quiz_type->count() > 0) {

            QuizPart::addGlobalScope(new CompanyScope());
            $query = QuizPart::query();
            $query->select(['el_quiz_part.id']);
            $query->leftjoin('el_quiz_register as b','b.part_id','=','el_quiz_part.id');
            $query->where('b.type', '=', 1);
            $query->whereIn('el_quiz_part.quiz_id', function ($sub2) use ($quiz_type){
                $sub2->select(['id'])
                    ->from('el_quiz')
                    ->where('status', '=', 1)
                    ->where('is_open', '=', 1)
                    ->where('type_id', '=', $quiz_type)
                    ->pluck('id')->toArray();
            });
            $query->where(\DB::raw('month('.$prefix.'el_quiz_part.start_date)'), '<=', $i)
                ->where(function ($sub) use ($prefix, $i){
                    $sub->orWhereNull('el_quiz_part.end_date');
                    $sub->orWhere(\DB::raw('month('.$prefix.'el_quiz_part.end_date)'), '>=', $i);
                });

            if ($this->unit || $this->unit_type || $this->area){
                $query->leftjoin('el_profile_view as c','c.user_id','=','b.user_id');
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
            $query->groupBy(['el_quiz_part.id']);
            $quiz_part_by_course = $query->get();
            $course_by_units = $quiz_part_by_course->count();

        } else {
            $course_by_units = 0;
        }


        return $course_by_units;
    }

    public function charts()
    {
        return [];
    }

    public function lineChart(){
        $dataSeriesLabels = [];
        for ($i = 24; $i <= (23 + $this->count); $i++){
            $dataSeriesLabels[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$'.$i, null, 1);
        }
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$23:$O$23', null, 12),
        ];
        $dataSeriesValues = [];
        for ($i = 24; $i <= (23 + $this->count); $i++) {
            $dataSeriesValues[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$'.$i.':$O$'.$i, null, 12);
        }

        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            DataSeries::GROUPING_STANDARD, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);
        $title = new Title('');

        $chart = new Chart(
            'chart1',
            $title,
            $legend,
            $plotArea
        );
        $chart->setTopLeftPosition('A10');
        $chart->setBottomRightPosition('H21');

        return $chart;
    }

    public function pieChart(){
        $dataSeriesLabels1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$23', null, 1),
        ];
        $xAxisTickValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$24:$B$'.(22 + $this->count), null, ($this->count - 1)),
        ];
        $dataSeriesValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$24:$C$'.(22 + $this->count), null, ($this->count - 1)),
        ];

        $series1 = new DataSeries(
            DataSeries::TYPE_PIECHART, // plotType
            null, // plotGrouping (Pie charts don't have any grouping)
            range(0, count($dataSeriesValues1) - 1), // plotOrder
            $dataSeriesLabels1, // plotLabel
            $xAxisTickValues1, // plotCategory
            $dataSeriesValues1          // plotValues
        );

        $layout1 = new Layout();
        $layout1->setShowVal(true);

        $plotArea1 = new PlotArea($layout1, [$series1]);
        $legend1 = new Legend(Legend::POSITION_RIGHT, null, false);
        $title1 = new Title('');

        $chart1 = new Chart(
            'chart1', // name
            $title1, // title
            $legend1, // legend
            $plotArea1 // plotArea
        );

        $chart1->setTopLeftPosition('J10');
        $chart1->setBottomRightPosition('O21');

        return $chart1;
    }
}
