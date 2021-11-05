<?php


namespace Modules\TrainingPlan\Exports;

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
use App\Models\Categories\TrainingObject;
use Modules\TrainingPlan\Entities\TrainingPlanDetail;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use Modules\TrainingPlan\Entities\TrainingPlan;
use App\TypeCost;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Config;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportTrainingPlanDetail implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents, WithStartRow, WithDrawings
{

    use Exportable;

    protected $index = 0;
    protected $count = 0;
    protected $count_title = 15;
    protected $count_organizational_start = 15;
    protected $count_organizational_end = 14;
    protected $total = 0;

    public function __construct($plan_id)
    {
        $this->plan_id = $plan_id;
    }

    public function map($row): array
    {
        $type_costs = TypeCost::get();
        $get_type_model_costs = json_decode($row->type_costs);
        $course_type = explode(',',$row->course_type);
        $training_form_array = [];
        $get_training_forms =  TrainingForm::whereIn('id',explode(',',$row->training_form_id))->get();
        foreach ($get_training_forms as $key => $get_training_form) {
            $training_form_array[] = $get_training_form->name;
        }
        $training_form = implode(', ',$training_form_array);

        $this->index++;

        $answer_name = [];
        $answer_name[] = $this->index;
        $answer_name[] = $row->subject_code;
        $answer_name[] = $row->subject_name;
        $answer_name[] = $row->training_program_name;
        $answer_name[] = (in_array(1,$course_type) ? 'Đào tạo Trực tuyến,' : ''). (in_array(2,$course_type) ? ' Đào tạo Tập trung,' : '');
        $answer_name[] = $training_form;
        $answer_name[] = $row->exis_training_CBNV ? number_format($row->exis_training_CBNV, 0) : 0;
        $answer_name[] = $row->recruit_training_CBNV ? number_format($row->recruit_training_CBNV, 0) : 0;
        $answer_name[] = $row->total_course;
        $answer_name[] = $row->periods;
        $answer_name[] = $row->quarter1;
        $answer_name[] = $row->quarter2;
        $answer_name[] = $row->quarter3;
        $answer_name[] = $row->quarter4;

        foreach ($type_costs as $key => $type_cost){
            if( !empty($get_type_model_costs[$key]) ) {
                $answer_name[] = number_format($get_type_model_costs[$key]->money_cost);
            } else {
                $answer_name[] = '0';
            }
        }

        $answer_name[] = $row->total_type_cost ? number_format($row->total_type_cost, 0) : '0';

        return [
           $answer_name,
        ];
    }

    public function query()
    {
        $query = TrainingPlanDetail::query();
        $query->select([
            'tpd.*',
            'tp.name as training_program_name',
            'ls.name as level_subject_name',
            'ls.code as level_subject_code',
            's.name as subject_name',
            's.code as subject_code',
        ]);
        $query->from('el_training_plan_detail AS tpd');
        $query->leftJoin('el_training_program AS tp', 'tp.id', '=', 'tpd.training_program_id');
        $query->leftJoin('el_level_subject AS ls', 'ls.id', '=', 'tpd.level_subject_id');
        $query->leftJoin('el_subject AS s', 's.id', '=', 'tpd.subject_id');
        $query->where('plan_id',$this->plan_id);
        $query->orderBy('tpd.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $type_costs = TypeCost::get();
        $plan_id = TrainingPlan::find($this->plan_id);

        $title_arr1[] = 'STT';                              $title_arr2[] = '';
        $title_arr1[] = trans('lacourse.course_code');                      $title_arr2[] = '';
        $title_arr1[] = trans('lacourse.course_name');                     $title_arr2[] = '';
        $title_arr1[] = 'Chương trình đào tạo';             $title_arr2[] = '';
        $title_arr1[] = 'Hình thức đào tạo';                $title_arr2[] = '';
        $title_arr1[] = 'Loại hình đào tạo';                $title_arr2[] = '';
        $title_arr1[] = 'Nhu cầu đào tạo hiện hữu';         $title_arr2[] = '';
        $title_arr1[] = 'Nhu cầu đào tạo tân tuyển';        $title_arr2[] = '';
        $title_arr1[] = 'Tổng số lớp';                      $title_arr2[] = '';
        $title_arr1[] = 'Thời lượng đào tạo lớp';           $title_arr2[] = '';
        $title_arr1[] = 'Kế hoạch đào tạo';                 $title_arr2[] = 'Quý 1';
        $title_arr1[] = '';                                 $title_arr2[] = 'Quý 2';
        $title_arr1[] = '';                                 $title_arr2[] = 'Quý 3';
        $title_arr1[] = '';                                 $title_arr2[] = 'Quý 4';

        foreach ($type_costs as $key => $type_cost){
            $title_arr1[] = ($key == 0 ? 'Chi phí đào tạo (ĐVT: VNĐ)' : '');
            $title_arr2[] = $type_cost->name;

            $this->count_title += 1;
            $this->count_organizational_end += 1;
        }
        $title_arr1[] = 'Tổng chi phí đào tạo';        $title_arr2[] = '';
        $this->total = ($this->count_organizational_end + 1);

        return [
            [],
            [],
            [],
            [],
            [],
            ['Chi tiết kế hoạch đào tạo'],
            [],
            $title_arr1,
            $title_arr2,
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

                $char = $this->getChar($this->count_title);

                $event->sheet->getDelegate()->mergeCells('A6:'.$char.'6')->getStyle('A6')->applyFromArray($title);

                $event->sheet->getDelegate()->mergeCells('K8:N8');

                $event->sheet->getDelegate()->getStyle('A8:'.$char.'9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:'.$char.''.(9 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $event->sheet->getDelegate()->mergeCells('A8:A9');
                $event->sheet->getDelegate()->mergeCells('B8:B9');
                $event->sheet->getDelegate()->mergeCells('C8:C9');
                $event->sheet->getDelegate()->mergeCells('D8:D9');
                $event->sheet->getDelegate()->mergeCells('E8:E9');
                $event->sheet->getDelegate()->mergeCells('F8:F9');
                $event->sheet->getDelegate()->mergeCells('G8:G9');
                $event->sheet->getDelegate()->mergeCells('H8:H9');
                $event->sheet->getDelegate()->mergeCells('I8:I9');
                $event->sheet->getDelegate()->mergeCells('J8:J9');

                $total = $this->getChar($this->total);
                $event->sheet->getDelegate()->mergeCells($total.'8:'.$total.'9');
                
                if ($this->count_organizational_end >= $this->count_organizational_start){
                    $organizational_start_char = $this->getChar($this->count_organizational_start);
                    $organizational_end_char = $this->getChar($this->count_organizational_end);

                    $event->sheet->getDelegate()->mergeCells($organizational_start_char.'8:'.$organizational_end_char.'8');
                }
            },
        ];
    }

    public function startRow(): int
    {
        return 10;
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

    public function getChar($number){
        $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        if ($number > 26){
            $num = floor($number/26);
            $num_1 = $number - ($num * 26);

            $char = $arr_char[($num - 1)] . $arr_char[($num_1 - 1)];
        }else{
            $char = $arr_char[($number - 1)];
        }

        return $char;
    }
}
