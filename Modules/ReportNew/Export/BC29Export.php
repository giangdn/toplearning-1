<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\CourseRegisterView;
use App\CourseView;
use App\LogoModel;
use App\Models\Categories\Subject;
use App\ProfileView;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizQuestionCategory;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\ReportNew\Entities\BC29;
use Modules\TrainingPlan\Entities\TrainingPlan;
use Modules\TrainingPlan\Entities\TrainingPlanDetail;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use function GuzzleHttp\json_decode;

class BC29Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 18;
    protected $arr_title = [];

    public function __construct($param)
    {
        $this->year = $param->year;
    }

    public function query()
    {
        $query = BC29::sql($this->year)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $obj = [];

        $this->index++;
        $obj[] = $this->index;

        $subject = Subject::find($row->subject_id);
        $training_plan = TrainingPlan::find($row->training_plan_id);
        $training_plan_detail = TrainingPlanDetail::wherePlanId($row->training_plan_id)->whereSubjectId($row->subject_id)->first();

        $obj[] = @$subject->code;
        $obj[] = @$subject->name;
        $obj[] = @$training_plan->code;
        $obj[] = @$training_plan->name;
        $obj[] = $row->course_action_1 == 1 ? 'X' : '';
        $obj[] = $row->course_action_2 == 1 ? 'X' : '';

        $quarter_course_1 = CourseView::query()
            ->where('subject_id', '=', $row->subject_id)
            ->where('in_plan', '=', $row->training_plan_id)
            ->where(\DB::raw('year(start_date)'), '=', $row->year)
            ->where(function($sub){
                $sub->orWhere(\DB::raw('month(start_date)'), '=', 1)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 2)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 3);
            })
            ->count();

        $quarter_course_2 = CourseView::query()
            ->where('subject_id', '=', $row->subject_id)
            ->where('in_plan', '=', $row->training_plan_id)
            ->where(\DB::raw('year(start_date)'), '=', $row->year)
            ->where(function($sub){
                $sub->orWhere(\DB::raw('month(start_date)'), '=', 4)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 5)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 6);
            })
            ->count();

        $quarter_course_3 = CourseView::query()
            ->where('subject_id', '=', $row->subject_id)
            ->where('in_plan', '=', $row->training_plan_id)
            ->where(\DB::raw('year(start_date)'), '=', $row->year)
            ->where(function($sub){
                $sub->orWhere(\DB::raw('month(start_date)'), '=', 7)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 8)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 9);
            })
            ->count();

        $quarter_course_4 = CourseView::query()
            ->where('subject_id', '=', $row->subject_id)
            ->where('in_plan', '=', $row->training_plan_id)
            ->where(\DB::raw('year(start_date)'), '=', $row->year)
            ->where(function($sub){
                $sub->orWhere(\DB::raw('month(start_date)'), '=', 10)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 11)
                    ->orWhere(\DB::raw('month(start_date)'), '=', 12);
            })
            ->count();

        $quarter_course_arr = [
            '1' => $quarter_course_1,
            '2' => $quarter_course_2,
            '3' => $quarter_course_3,
            '4' => $quarter_course_4,
        ];

        $prefix = DB::getTablePrefix();
        $quarter_user_1 = CourseRegisterView::query()
            ->from('el_course_register_view as a')
            ->leftJoin('el_course_view as b', function ($sub){
                $sub->on('a.course_id', '=', 'b.course_id');
                $sub->on('a.course_type', '=', 'b.course_type');
            })
            ->where('b.subject_id', '=', $row->subject_id)
            ->where('b.in_plan', '=', $row->training_plan_id)
            ->where(\DB::raw('year('.$prefix.'a.created_at)'), '=', $row->year)
            ->where(function($sub) use ($prefix){
                $sub->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 1)
                    ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 2)
                    ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 3);
            })
            ->count();

        $quarter_user_2 = CourseRegisterView::query()
            ->from('el_course_register_view as a')
            ->leftJoin('el_course_view as b', function ($sub){
                $sub->on('a.course_id', '=', 'b.course_id');
                $sub->on('a.course_type', '=', 'b.course_type');
            })
            ->where('b.subject_id', '=', $row->subject_id)
            ->where('b.in_plan', '=', $row->training_plan_id)
            ->where(\DB::raw('year('.$prefix.'a.created_at)'), '=', $row->year)
            ->where(function($sub) use ($prefix){
                $sub->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 4)
                    ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 5)
                    ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 6);
            })
            ->count();

        $quarter_user_3 = CourseRegisterView::query()
            ->from('el_course_register_view as a')
            ->leftJoin('el_course_view as b', function ($sub){
                $sub->on('a.course_id', '=', 'b.course_id');
                $sub->on('a.course_type', '=', 'b.course_type');
            })
            ->where('b.subject_id', '=', $row->subject_id)
            ->where('b.in_plan', '=', $row->training_plan_id)
            ->where(\DB::raw('year('.$prefix.'a.created_at)'), '=', $row->year)
            ->where(function($sub) use ($prefix){
                $sub->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 7)
                    ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 8)
                    ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 9);
            })
            ->count();

        $quarter_user_4 = CourseRegisterView::query()
            ->from('el_course_register_view as a')
            ->leftJoin('el_course_view as b', function ($sub){
                $sub->on('a.course_id', '=', 'b.course_id');
                $sub->on('a.course_type', '=', 'b.course_type');
            })
            ->where('b.subject_id', '=', $row->subject_id)
            ->where('b.in_plan', '=', $row->training_plan_id)
            ->where(\DB::raw('year('.$prefix.'a.created_at)'), '=', $row->year)
            ->where(function($sub) use ($prefix){
                $sub->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 10)
                    ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 11)
                    ->orWhere(\DB::raw('month('.$prefix.'a.created_at)'), '=', 12);
            })
            ->count();

        $quarter_user_arr = [
            '1' => $quarter_user_1,
            '2' => $quarter_user_2,
            '3' => $quarter_user_3,
            '4' => $quarter_user_4,
        ];

        $plan_year = 0;
        $perform_year = 0;
        for ($i = 1; $i <= 4; $i++){
            if ($i > 1){
                $obj[] = @$training_plan_detail->{'quarter'.$i}.'';
                $obj[] = $quarter_course_arr[$i].'';
                $obj[] = number_format($quarter_course_arr[$i]/(@$training_plan_detail->{'quarter'.$i} > 0 ? @$training_plan_detail->{'quarter'.$i} : 1) * 100, 2).'';

                $obj[] = ($row->{'plan_accumulated_precious_'.($i-1)} + @$training_plan_detail->{'quarter'.$i}).'';
                $obj[] = ($row->{'perform_accumulated_precious_'.($i-1)} + $quarter_course_arr[$i]).'';

                $row->{'plan_accumulated_precious_'.$i} = $row->{'plan_accumulated_precious_'.($i-1)} + @$training_plan_detail->{'quarter'.$i};
                $row->{'perform_accumulated_precious_'.$i} = $row->{'perform_accumulated_precious_'.($i-1)} + $quarter_course_arr[$i];

                $obj[] = number_format($row->{'perform_accumulated_precious_'.$i}/($row->{'plan_accumulated_precious_'.$i} > 0 ? $row->{'plan_accumulated_precious_'.$i} : 1) * 100, 2).'';

                $obj[] = $quarter_user_arr[$i].'';
                $obj[] = ($row->{'student_accumulated_precious_'.($i-1)} + $quarter_user_arr[$i]).'';

                $row->{'student_accumulated_precious_'.$i} = $row->{'student_accumulated_precious_'.($i-1)} + $quarter_user_arr[$i];
            }else{
                $obj[] = @$training_plan_detail->{'quarter'.$i}.'';
                $obj[] = $quarter_course_arr[$i].'';
                $obj[] = number_format($quarter_course_arr[$i]/(@$training_plan_detail->{'quarter'.$i} > 0 ? @$training_plan_detail->{'quarter'.$i} : 1) * 100, 2).'';
                $obj[] = $quarter_user_arr[$i].'';

                $row->{'plan_accumulated_precious_'.$i} = @$training_plan_detail->{'quarter'.$i};
                $row->{'perform_accumulated_precious_'.$i} = $quarter_course_arr[$i];
                $row->{'student_accumulated_precious_'.$i} = $quarter_user_arr[$i];
            }

            $plan_year += @$training_plan_detail->{'quarter'.$i};
            $perform_year += $quarter_course_arr[$i];
        }

        $obj[] = $plan_year.'';
        $obj[] = $perform_year.'';
        $obj[] = number_format($perform_year/($plan_year > 0 ? $plan_year : 1) * 100, 2).'';

        return $obj;
    }

    public function headings(): array
    {
        $title_arr[] = 'STT';
        $title_arr[] = trans('lacourse.course_code');
        $title_arr[] = trans('lacourse.course_name');
        $title_arr[] = 'Mã kế hoạch';
        $title_arr[] = 'Tên kế hoạch';
        $title_arr[] = 'Kế hoạch';
        $title_arr[] = 'Phát sinh';
        $title_arr[] = 'Quý 1';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = 'Quý 2';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = 'Quý 3';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = 'Quý 4';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = '';
        $title_arr[] = 'Năm';
        $title_arr[] = '';
        $title_arr[] = '';

        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';
        $title_arr2[] = '';

        $title_arr2[] = 'Kế hoạch';
        $title_arr2[] = 'Thực hiện';
        $title_arr2[] = 'Tỷ lệ (%)';
        $title_arr2[] = 'Số lượt HV';

        $title_arr2[] = 'Kế hoạch';
        $title_arr2[] = 'Thực hiện';
        $title_arr2[] = 'Tỷ lệ (%)';
        $title_arr2[] = 'Kế hoạch lũy kế';
        $title_arr2[] = 'Thực hiện lũy kế';
        $title_arr2[] = 'Tỷ lệ (%)';
        $title_arr2[] = 'Số lượt HV';
        $title_arr2[] = 'Số lượt HV lũy kế';

        $title_arr2[] = 'Kế hoạch';
        $title_arr2[] = 'Thực hiện';
        $title_arr2[] = 'Tỷ lệ (%)';
        $title_arr2[] = 'Kế hoạch lũy kế';
        $title_arr2[] = 'Thực hiện lũy kế';
        $title_arr2[] = 'Tỷ lệ (%)';
        $title_arr2[] = 'Số lượt HV';
        $title_arr2[] = 'Số lượt HV lũy kế';

        $title_arr2[] = 'Kế hoạch';
        $title_arr2[] = 'Thực hiện';
        $title_arr2[] = 'Tỷ lệ (%)';
        $title_arr2[] = 'Kế hoạch lũy kế';
        $title_arr2[] = 'Thực hiện lũy kế';
        $title_arr2[] = 'Tỷ lệ (%)';
        $title_arr2[] = 'Số lượt HV';
        $title_arr2[] = 'Số lượt HV lũy kế';

        $title_arr2[] = 'Kế hoạch';
        $title_arr2[] = 'Thực hiện';
        $title_arr2[] = 'Tỷ lệ (%)';

        return [
            [],
            [],
            [],
            [],
            [],
            ['BÁO CÁO KẾT QUẢ THỰC HIỆN SO VỚI KẾ HOẠCH QUÝ/NĂM'],
            [],
            $title_arr,
            $title_arr2
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

                $event->sheet->getDelegate()->mergeCells('A6:AL6')
                ->getStyle('A6')
                ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:AL9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:AL'.(9 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                $event->sheet->getDelegate()->mergeCells('A8:A9');
                $event->sheet->getDelegate()->mergeCells('B8:B9');
                $event->sheet->getDelegate()->mergeCells('C8:C9');
                $event->sheet->getDelegate()->mergeCells('D8:D9');
                $event->sheet->getDelegate()->mergeCells('E8:E9');
                $event->sheet->getDelegate()->mergeCells('F8:F9');
                $event->sheet->getDelegate()->mergeCells('G8:G9');

                $event->sheet->getDelegate()->mergeCells('H8:K8');
                $event->sheet->getDelegate()->mergeCells('L8:S8');
                $event->sheet->getDelegate()->mergeCells('T8:AA8');
                $event->sheet->getDelegate()->mergeCells('AB8:AI8');
                $event->sheet->getDelegate()->mergeCells('AJ8:AL8');
            },

        ];
    }

    public function startRow(): int
    {
        return 9;
    }

    public function drawings()
    {
        $storage = \Storage::disk('upload');

        LogoModel::addGlobalScope(new CompanyScope());
        $logo = LogoModel::where('status',1)->first();
        if ($logo) {
            $path = $storage->path($logo->image);
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
}
