<?php
namespace Modules\Report\Export;
use App\Config;
use App\Models\Categories\Unit;
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
use Modules\Offline\Entities\OfflineRegister;
use Modules\Report\Entities\BC10;
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

class BC10Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    private $from_date;
    private $to_date;
    private $type;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->type = $param->type;
    }

    public function query()
    {
        $query = BC10::sql($this->from_date, $this->to_date, $this->type)->orderBy('id');
        return $query;
    }
    public function map($report): array
    {
        $query = DB::query();
        $query->select(['id'])
            ->from('el_offline_register')
            ->where('course_id', '=', $report->id)
            ->where('status', '=', 1);
        $count_regid = $query->count();

        $query1 = DB::query();
        $query1->select(['a.id'])
            ->from('el_offline_attendance AS a')
            ->leftJoin('el_offline_register AS b', 'b.id', '=', 'a.register_id')
            ->where('b.course_id', '=', $report->id)
            ->where('b.status', '=', 1)
            ->where('a.status', '=', 1);
        $count_atten = $query1->count();
        $count_no_atten = $count_regid - $count_atten;

        $query2 = DB::query();
        $query2->select(['a.id'])
            ->from('el_offline_result AS a')
            ->leftJoin('el_offline_register AS b', 'b.id', '=', 'a.register_id')
            ->where('b.course_id', '=', $report->id)
            ->where('b.status', '=', 1)
            ->where('a.result', '=', 1);
        $count_result = $query2->count();
        $count_no_result = $count_regid - $count_result;

        $query3 = DB::query();
        $query3->select(['id', 'actual_amount'])
            ->from('el_offline_course_cost')
            ->where('course_id', '=', $report->id);
        $actual_amounts = $query3->get();

        $query4 = DB::query();
        $query4->select(['a.id', 'a.cost'])
            ->from('el_offline_student_cost AS a')
            ->leftJoin('el_offline_register AS b', 'b.id', '=', 'a.register_id')
            ->where('b.course_id', '=', $report->id)
            ->where('b.status', '=', 1);
        $costs = $query4->get();

        $total = 0;
        foreach ($actual_amounts as $item){
            $total += $item->actual_amount;
        }
        foreach ($costs as $item){
            $total += $item->cost;
        }

        $sum_cost = $total;

        $this->index++;
        $profile = Profile::find($report->user_id);
        $arr_unit = Unit::getTreeParentUnit(@$profile->unit_code);

        $course_time = preg_replace("/[^0-9]/", '', $report->course_time);
        $course_time_unit = preg_replace("/[^a-z]/", '', $report->course_time);
        switch ($course_time_unit){
            case 'day': $time_unit = 'Ngày'; break;
            case 'session': $time_unit = 'Buổi'; break;
            default : $time_unit = 'Giờ'; break;
        }

        return [
            $this->index,
            $report->teacher_code,
            $report->teacher_name,
            $report->training_unit,
            $report->title_name,
            $arr_unit ? $arr_unit[$profile->unit->level]->name : '',
            $arr_unit ? $arr_unit[$profile->unit->level - 1]->name : '',
            $arr_unit ? $arr_unit[2]->name : '',
            $report->course_code,
            $report->course_name,
            $this->type == 1 ? 'Nội bộ' : 'Thuê ngoài',
            $course_time ? $course_time . ' ' . $time_unit : '',
            get_date($report->start_date),
            get_date($report->end_date),
            $count_regid ? $count_regid : 0,
            $count_atten ? $count_atten : 0,
            $count_no_atten,
            $count_result ? $count_result : 0,
            $count_no_result,
            $sum_cost,
        ];
    }
    public function headings(): array
    {
        return [
            [],
            [],
            [],
            [],
            ['DANH SÁCH GIẢNG VIÊN THAM GIA GIẢNG DẠY'],
            ['Từ '.$this->from_date.' - '.$this->to_date],
            [
                'STT',
                'Mã GV',
                'Họ Tên GV',
                'Đơn vị đào tạo',
                'Chức danh',
                'Đơn vị công tác','','',
                trans('lacourse.course_code'),
                'Khóa học',
                'Hình thức',
                'Thời lượng',
                'Ngày bắt đầu',
                'Ngày kết thúc',
                'Tổng số lượt',
                'Tham gia',
                '',
                'Hoàn thành',
                '',
                'Chi phí'
            ],
            [
                '',
                '',
                '',
                '',
                '',
                'Đơn vị trực tiếp','Đơn vị gián tiếp 1', 'Công ty',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Có', 'Không',
                'Có', 'Không',
                ''
            ]
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

                $event->sheet->getDelegate()->mergeCells('A5:T5')->getStyle('A5:T5')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A6:T6')->getStyle('A6:T6')->applyFromArray($title);

                $event->sheet->getDelegate()->mergeCells('A7:A8');
                $event->sheet->getDelegate()->mergeCells('B7:B8');
                $event->sheet->getDelegate()->mergeCells('C7:C8');
                $event->sheet->getDelegate()->mergeCells('D7:D8');
                $event->sheet->getDelegate()->mergeCells('E7:E8');
                $event->sheet->getDelegate()->mergeCells('I7:I8');
                $event->sheet->getDelegate()->mergeCells('J7:J8');
                $event->sheet->getDelegate()->mergeCells('K7:K8');
                $event->sheet->getDelegate()->mergeCells('L7:L8');
                $event->sheet->getDelegate()->mergeCells('M7:M8');
                $event->sheet->getDelegate()->mergeCells('N7:N8');
                $event->sheet->getDelegate()->mergeCells('O7:O8');
                $event->sheet->getDelegate()->mergeCells('T7:T8');

                $event->sheet->getDelegate()->mergeCells('F7:H7');
                $event->sheet->getDelegate()->mergeCells('P7:Q7');
                $event->sheet->getDelegate()->mergeCells('R7:S7');

                $event->sheet->getDelegate()
                    ->getStyle("A7:T8")
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A7:T'.(8 + $this->index))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],

                        ],
                        'font' => [
                            'name' => 'Arial',
                            'size' =>  12,
                        ],
                    ]);
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
