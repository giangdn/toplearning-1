<?php


namespace Modules\DashboardUnit\Exports;

use App\ProfileView;
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
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
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

class ExportDashboardUserQuizSheet1 implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithStartRow, WithDrawings, WithCharts, WithTitle
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
        $profile = ProfileView::whereUserId(@$row->user_id)->first();
        $quiz = Quiz::find(@$row->quiz_id);
        $quiz_type = QuizType::whereId(@$quiz->type_id)->first();
        $quiz_part = QuizPart::find(@$row->part_id);
        $result = QuizResult::where('quiz_id', '=', @$row->quiz_id)
            ->where('user_id', '=', @$row->user_id)
            ->where('type', '=', @$row->type)
            ->first();

        return [
            $this->index,
            @$profile->code,
            @$profile->full_name,
            @$profile->title_name,
            @$profile->unit_name,
            @$profile->parent_unit_name,
            isset($profile->dob) ? get_date($profile->dob) : '',
            @$profile->identity_card,
            @$profile->email,
            @$quiz->name,
            @$quiz_type->name,
            @$quiz_part->name,
            $result ? number_format($result->grade, 2) : '',
            $result ? 'Hoàn thành' : 'Không hoàn thành',
            $result ? ($result->result == 1 ? 'Đạt' : 'Không đạt') : '',
        ];
    }

    public function query()
    {
        $unit_user = $this->unit_user;
        $child_arr = $this->child_arr;

        QuizPart::addGlobalScope(new CompanyScope());
        $query = QuizPart::query();
        $query->select([
            'el_quiz_part.id',
            'b.quiz_id',
            'b.user_id',
            'b.part_id',
            'b.type',
        ]);
        $query->leftJoin('el_quiz_register as b','b.part_id','=','el_quiz_part.id');
        $query->where('b.type', '=', 1);
        $query->whereIn('el_quiz_part.quiz_id', function ($sub2) {
            $sub2->select(['id'])
                ->from('el_quiz')
                ->where('status', '=', 1)
                ->where('is_open', '=', 1)
                ->pluck('id')->toArray();
        });
        $query->whereExists(function ($sub) {
            $sub->select(['id'])
                ->from('el_quiz_result as result')
                ->whereColumn('result.user_id', '=', 'b.user_id')
                ->whereColumn('result.quiz_id', '=', 'b.quiz_id')
                ->whereColumn('result.type', '=', 'b.type');
        });
        $query->orderBy('el_quiz_part.id', 'ASC');
        $this->count = $query->count();

        return $query;
    }

    public function headings(): array
    {
        $area = Area::find($this->area);
        $export_name = 'Thống kê lượt CBNV thi theo loại kỳ thi';

        $title_arr[] = 'STT';
        $title_arr[] = 'Mã nhân viên';
        $title_arr[] = 'Họ và tên';
        $title_arr[] = 'Chức danh';
        $title_arr[] = 'Đơn vị trực tiếp';
        $title_arr[] = 'Đơn vị quản lý';
        $title_arr[] = 'Ngày sinh';
        $title_arr[] = 'CMND';
        $title_arr[] = 'Email';
        $title_arr[] = 'Kỳ thi';
        $title_arr[] = 'Loại kỳ thi';
        $title_arr[] = 'Ca thi';
        $title_arr[] = 'Điểm';
        $title_arr[] = 'Hoàn thành / Không hoàn thành';
        $title_arr[] = 'Kết quả';

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
            $title_arr,
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

                $event->sheet->getDelegate()->getStyle('A23:O23')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A23:O'.(23 + $this->count))->applyFromArray([
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

    public function countChart() {
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

        return $query->get()->count();
    }

    public function charts()
    {
        return [];
    }

    public function lineChart(){
        $count = $this->countChart();

        $dataSeriesLabels = [];
        for ($i = 24; $i <= (23 + $count); $i++){
            $dataSeriesLabels[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Sheet2!$B$'.$i, null, 1);
        }
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Sheet2!$D$23:$O$23', null, 12),
        ];
        $dataSeriesValues = [];
        for ($i = 24; $i <= (23 + $count); $i++) {
            $dataSeriesValues[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Sheet2!$D$'.$i.':$O$'.$i, null, 12);
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
        $count = $this->countChart();

        $dataSeriesLabels1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Sheet2!$C$23', null, 1),
        ];
        $xAxisTickValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Sheet2!$B$24:$B$'.(22 + $count), null, ($count - 1)),
        ];
        $dataSeriesValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Sheet2!$C$24:$C$'.(22 + $count), null, ($count - 1)),
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

    public function title(): string
    {
        return 'Sheet1';
    }
}
