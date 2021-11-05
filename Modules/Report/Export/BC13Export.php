<?php
namespace Modules\Report\Export;

use App\Config;
use App\Models\Categories\Unit;
use App\PlanApp;
use App\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineResult;
use Modules\Rating\Entities\RatingCourse;
use Modules\Report\Entities\BC13;
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
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BC13Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->course_type = $param->type;
    }

    public function query()
    {
        $query = BC13::sql($this->course_type, $this->from_date, $this->to_date)->orderBy('id', 'ASC');

        $this->count = $query->count();
        return $query;
    }
    public function map($report): array
    {
        if ($report->course_type == 1){
            $onl_result = OnlineResult::where('register_id', '=', $report->id)
                ->where('course_id', '=', $report->course_id)
                ->where('user_id', '=', $report->user_id)
                ->first('result');
        }else{
            $off_result = OfflineResult::where('register_id', '=', $report->id)
                ->where('course_id', '=', $report->course_id)
                ->where('user_id', '=', $report->user_id)
                ->first('result');
        }

        $plan_app = PlanApp::where('course_type', '=', $report->course_type)
            ->where('course_id', '=', $report->course_id)
            ->where('user_id', '=', $report->user_id)
            ->first();

        $this->index++;
        $profile = Profile::find($report->user_id);
        $arr_unit = Unit::getTreeParentUnit($profile->unit_code);

        return [
            $this->index,
            $report->code,
            $report->lastname . ' ' . $report->firstname,
            $report->title_name,
            $arr_unit ? $arr_unit[$profile->unit->level]->name : '',
            $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '',
            $arr_unit ? $arr_unit[2]->name : '',
            $report->course_code,
            $report->course_name,
            get_date($report->start_date, 'd/m/Y'),
            get_date($report->end_date, 'd/m/Y'),
            $report->course_type == 1 ? ($onl_result && $onl_result->result == 1 ? '' : 'x') : ($off_result && $off_result->result == 1 ? '' : 'x'),
            $plan_app && $plan_app->status < 1 ? 'x' : '',
            '',
        ];
    }
    public function headings(): array
    {
        return [
            [],
            [],
            [],
            [],
            [],
            [],
            ['Danh sách vi phạm'],
            ['Từ: ' . $this->from_date .' - '. $this->to_date],
            ['STT', 'MSNV', 'Họ tên', 'Chức danh', 'Đơn vị','','', trans('lacourse.course_code'), 'Khóa học', 'Thời gian', '', 'Kết quả (Không đạt)', 'Chưa nộp bản thu hoạch', 'Hình thức kỹ luật'],
            ['', '', '', '', 'Đơn vị trực tiếp', 'Đơn vị gián tiếp', 'Công ty', '', '', 'Từ ngày', 'Đến ngày', '', '', '']
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
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $event->sheet->getDelegate()->mergeCells('A7:N7')->getStyle('A7')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A8:N8')->getStyle('A8')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A9:N10')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->mergeCells('A9:A10');
                $event->sheet->getDelegate()->mergeCells('B9:B10');
                $event->sheet->getDelegate()->mergeCells('C9:C10');
                $event->sheet->getDelegate()->mergeCells('D9:D10');
                $event->sheet->getDelegate()->mergeCells('H9:H10');
                $event->sheet->getDelegate()->mergeCells('I9:I10');
                $event->sheet->getDelegate()->mergeCells('L9:L10');
                $event->sheet->getDelegate()->mergeCells('M9:M10');
                $event->sheet->getDelegate()->mergeCells('N9:N10');

                $event->sheet->getDelegate()->mergeCells('E9:G9');
                $event->sheet->getDelegate()->mergeCells('J9:K9');

                $event->sheet->getDelegate()->getStyle('A7:N'.(10 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },

        ];
    }
    public function startRow(): int
    {
        return 11;
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
}
