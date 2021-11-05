<?php
namespace Modules\ReportNew\Export;

use App\Config;
use App\LogoModel;
use App\Models\Categories\StudentCost;
use App\Models\Categories\TrainingCost;
use App\Scopes\CompanyScope;
use App\TypeCost;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\ReportNew\Entities\BC08;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\Unit;
use Modules\Offline\Entities\OfflineCourse;

class BC08Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow, WithDrawings
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;
    protected $count_title = 33;
    protected $num_char = [];

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->training_type_id = $param->training_type_id;
        $this->title_id = $param->title_id;
    }

    public function query()
    {
        $query = BC08::sql($this->from_date, $this->to_date, $this->training_type_id, $this->title_id)->orderBy('id', 'asc');

        $this->count = $query->count();
        return $query;
    }
    public function map($row): array
    {
        $offline = OfflineCourse::find($row->course_id);
        $unit_name = [];
        !empty($offline->training_unit) ? $training_unit = json_decode($offline->training_unit) : $training_unit = [];
        if($offline->training_unit_type == 0 && !empty($training_unit)) {
            $units = Unit::where('status', '=', 1)->get();
            foreach ($units as $key => $unit) {
                if(in_array($unit->id, $training_unit)) {
                    $unit_name[] = $unit->name;
                }
            }
        } else if ($offline->training_unit_type == 1 && !empty($training_unit)) {
            $training_partners = TrainingPartner::get();
            foreach ($training_partners as $key => $training_partner) {
                if(in_array($training_partner->id, $training_unit)) {
                    $unit_name[] = $training_partner->name;
                }
            }
        }
        $row->training_unit = !empty($unit_name) ? implode(',',$unit_name) : '';

        $schedules = OfflineSchedule::query()
            ->select(['a.end_time', 'a.lesson_date'])
            ->from('el_offline_schedule as a')
            ->where('a.course_id', '=', $row->course_id)
            ->get();

        foreach ($schedules as $schedule){
            if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                $row->time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
            }else{
                $row->time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
            }
        }

        $teacher_account_number = OfflineTeacher::query()
            ->from('el_offline_course_teachers AS a')
            ->join('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id')
            ->where('a.course_id', '=', $row->course_id)
            ->whereIn('a.teacher_id', function ($sub) use ($row){
                $sub->select(['teacher_main_id'])
                    ->from('el_offline_schedule')
                    ->where('course_id', '=', $row->course_id)
                    ->pluck('teacher_main_id')
                    ->toArray();
            })
            ->pluck('b.account_number')
            ->toArray();

        $obj = [];
        $this->index++;
        $row->start_date = get_date($row->start_date);
        $row->end_date = get_date($row->end_date);
        $row->recruits = $row->recruits == 1 ? 'X' : '';
        $row->exist = $row->exist == 1 ? 'X' : '';
        $row->plan = $row->plan == 1 ? 'X' : '';
        $row->incurred = $row->incurred == 1 ? 'X' : '';

        $obj[] = $this->index;
        $obj[] = $row->course_code;
        $obj[] = $row->course_name;
        $obj[] = $row->lecturer;
        $obj[] = $row->tuteurs;
        $obj[] = $row->training_form_name;
        $obj[] = $row->training_type_name;
        $obj[] = $row->level_subject;
        $obj[] = $row->training_location;
        $obj[] = $row->training_unit;
        $obj[] = $row->title_join;
        $obj[] = $row->training_object;
        $obj[] = $row->course_time;
        $obj[] = $row->start_date;
        $obj[] = $row->end_date;
        $obj[] = $row->time_schedule;
        $obj[] = $row->created_by;
        $obj[] = $row->registers;
        $obj[] = $row->join_100;
        $obj[] = $row->join_75;
        $obj[] = $row->join_below_75;
        $obj[] = $row->students_absent;
        $obj[] = $row->students_pass;
        $obj[] = $row->students_fail;

        $training_cost = TrainingCost::query()->orderBy('type')->orderBy('id')->pluck('id')->toArray();
        $student_cost = StudentCost::whereStatus(1)->pluck('id')->toArray();

        $course_cost = json_decode($row->course_cost, true);
        foreach ($training_cost as $cost_id){
            $obj[] = isset($course_cost['cost_'.$cost_id]) ? number_format($course_cost['cost_'.$cost_id], 2) : 0;
        }

        $student = json_decode($row->student_cost, true);
        foreach ($student_cost as $student_id){
            $obj[] = isset($student['student'.$student_id]) ? number_format($student['student'.$student_id], 2) : 0;
        }
        $obj[] = $student['student_cost_total'];

        $obj[] = number_format($row->total_cost, 2);
        $obj[] = $row->recruits;
        $obj[] = $row->exist;
        $obj[] = $row->plan;
        $obj[] = $row->incurred;
        $obj[] = $row->monitoring_staff;
        $obj[] = $row->monitoring_staff_note;
        $obj[] = $row->teacher_note;
        $obj[] = implode('; ', $teacher_account_number).' ';

        return $obj;
    }

    public function headings(): array
    {
        $type_cost = TypeCost::query()
            ->whereExists(function ($sub){
                $sub->select(['id'])
                    ->from('el_training_cost')
                    ->whereColumn('type', '=', 'el_type_cost.id');
            })
            ->orderBy('id')
            ->get(['id', 'name']);
        $student_cost = StudentCost::whereStatus(1)->get();

        $title_arr1 = [];
        $title_arr2 = [];

        $title_arr1[] = 'STT';                              $title_arr2[] = '';
        $title_arr1[] = trans('lacourse.course_code');                      $title_arr2[] = '';
        $title_arr1[] = trans('lacourse.course_name');                     $title_arr2[] = '';
        $title_arr1[] = 'Giảng viên';                       $title_arr2[] = '';
        $title_arr1[] = 'Trợ giảng';                        $title_arr2[] = '';
        $title_arr1[] = 'Hình thức đào tạo';                $title_arr2[] = '';
        $title_arr1[] = 'Loại hình đào tạo';                $title_arr2[] = '';
        $title_arr1[] = 'Mảng nghiệp vụ';                   $title_arr2[] = '';
        $title_arr1[] = 'Địa điểm đào tạo';                 $title_arr2[] = '';
        $title_arr1[] = 'Đơn vị đào tạo';                   $title_arr2[] = '';
        $title_arr1[] = 'Chức danh tham gia (bắt buộc)';    $title_arr2[] = '';
        $title_arr1[] = 'Nhóm đối tượng tham gia';          $title_arr2[] = '';
        $title_arr1[] = 'Thời lượng';                       $title_arr2[] = '';
        $title_arr1[] = 'Từ ngày';                          $title_arr2[] = '';
        $title_arr1[] = 'Đến ngày';                         $title_arr2[] = '';
        $title_arr1[] = 'Thời gian';                        $title_arr2[] = '';
        $title_arr1[] = trans('lageneral.creator');                        $title_arr2[] = '';
        $title_arr1[] = 'Số HV trong danh sách';            $title_arr2[] = '';
        $title_arr1[] = 'Số HV tham dự';                    $title_arr2[] = '100%';
        $title_arr1[] = '';                                 $title_arr2[] = '≥75%';
        $title_arr1[] = '';                                 $title_arr2[] = '<75%';
        $title_arr1[] = 'Số HV Vắng';                       $title_arr2[] = '';
        $title_arr1[] = 'Số HV đạt';                        $title_arr2[] = '';
        $title_arr1[] = 'Số HV không đạt';                  $title_arr2[] = '';

        $num_type_cost = 25;
        foreach ($type_cost as $type_key => $type){
            $title_arr1[] = $type->name;

            $this->num_char['start_type'.$type->id] = $num_type_cost;

            $training_cost = TrainingCost::where('type', '=', $type->id)->orderBy('id')->get();
            foreach ($training_cost as $key => $cost){
                $title_arr2[] = $cost->name;
                if ($key > 0){
                    $title_arr1[] = '';
                    $num_type_cost += 1;
                }

                $this->count_title += 1;
            }
            $this->num_char['end_type'.$type->id] = $num_type_cost;
            $num_type_cost += 1;
        }

        $num_student_cost = $num_type_cost;
        if ($student_cost->count() > 0){
            $this->num_char['start_student'] = $num_student_cost;
            foreach ($student_cost as $key => $student){
                $title_arr1[] = ($key == 0 ? 'CP Học viên' : '');
                $title_arr2[] = $student->name;

                if ($key > 0){
                    $num_student_cost += 1;
                }
                $this->count_title += 1;
            }
            $title_arr1[] = '';                      $title_arr2[] = 'Tổng CP Học viên';
            $num_student_cost += 1;

            $this->count_title += 1;
            $this->num_char['end_student'] = $num_student_cost;
        }else{
            $title_arr1[] = 'CP Học viên';                      $title_arr2[] = 'Tổng CP Học viên';
            $this->num_char['start_student'] = $num_student_cost;
            $this->num_char['end_student'] = $num_student_cost;
            $this->count_title += 1;
        }

        $title_arr1[] = 'Tổng chi phí';                     $title_arr2[] = '';
        $title_arr1[] = 'Tân tuyển';                        $title_arr2[] = '';
        $title_arr1[] = 'Hiện hữu';                         $title_arr2[] = '';
        $title_arr1[] = 'Kế hoạch';                         $title_arr2[] = '';
        $title_arr1[] = 'Phát sinh';                        $title_arr2[] = '';
        $title_arr1[] = 'Cán bộ theo dõi';                  $title_arr2[] = '';
        $title_arr1[] = 'Ý kiến cán bộ';                    $title_arr2[] = '';
        $title_arr1[] = 'Ý kiến giảng viên';                $title_arr2[] = '';
        $title_arr1[] = 'STK giảng viên';                   $title_arr2[] = '';

        $this->num_char['count_total_cost'] = ($this->num_char['end_student'] + 1);
        $this->num_char['count_recruits'] = ($this->num_char['end_student'] + 2);
        $this->num_char['count_exist'] = ($this->num_char['end_student'] + 3);
        $this->num_char['count_plan'] = ($this->num_char['end_student'] + 4);
        $this->num_char['count_incurred'] = ($this->num_char['end_student'] + 5);
        $this->num_char['count_monitoring_staff'] = ($this->num_char['end_student'] + 6);
        $this->num_char['count_monitoring_staff_note'] = ($this->num_char['end_student'] + 7);
        $this->num_char['count_teacher_note'] = ($this->num_char['end_student'] + 8);
        $this->num_char['count_teacher_account_number'] = ($this->num_char['end_student'] + 9);

        return [
            [],
            [],
            [],
            [],
            [],
            ['TỔNG HỢP TÌNH HÌNH TỔ CHỨC CÁC KHÓA HỌC NỘI BỘ VÀ BÊN NGOÀI'],
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

                $char_title = $this->getChar($this->count_title);

                $event->sheet->getDelegate()->mergeCells('A6:'.$char_title.'6')
                    ->getStyle('A6')
                    ->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:'.$char_title.'9')
                    ->applyFromArray($title)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFFF00');

                $event->sheet->getDelegate()->getStyle('A8:'.$char_title.(9 + $this->index))
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
                $event->sheet->getDelegate()->mergeCells('H8:H9');
                $event->sheet->getDelegate()->mergeCells('I8:I9');
                $event->sheet->getDelegate()->mergeCells('J8:J9');
                $event->sheet->getDelegate()->mergeCells('K8:K9');
                $event->sheet->getDelegate()->mergeCells('L8:L9');
                $event->sheet->getDelegate()->mergeCells('M8:M9');
                $event->sheet->getDelegate()->mergeCells('N8:N9');
                $event->sheet->getDelegate()->mergeCells('O8:O9');
                $event->sheet->getDelegate()->mergeCells('P8:P9');
                $event->sheet->getDelegate()->mergeCells('Q8:Q9');
                $event->sheet->getDelegate()->mergeCells('R8:R9');
                $event->sheet->getDelegate()->mergeCells('S8:U8');
                $event->sheet->getDelegate()->mergeCells('V8:V9');
                $event->sheet->getDelegate()->mergeCells('W8:W9');
                $event->sheet->getDelegate()->mergeCells('X8:X9');

                $type_cost = TypeCost::query()
                    ->whereExists(function ($sub){
                        $sub->select(['id'])
                            ->from('el_training_cost')
                            ->whereColumn('type', '=', 'el_type_cost.id');
                    })
                    ->orderBy('id')
                    ->get(['id']);
                foreach ($type_cost as $type){
                    $start_char = $this->getChar($this->num_char['start_type'.$type->id]);
                    $end_char = $this->getChar($this->num_char['end_type'.$type->id]);

                    $event->sheet->getDelegate()->mergeCells($start_char.'8:'.$end_char.'8');
                }

                $start_student = $this->getChar($this->num_char['start_student']);
                $end_student = $this->getChar($this->num_char['end_student']);
                $event->sheet->getDelegate()->mergeCells($start_student.'8:'.$end_student.'8');

                $total_cost_char = $this->getChar($this->num_char['count_total_cost']);
                $recruits_char = $this->getChar($this->num_char['count_recruits']);
                $exist_char = $this->getChar($this->num_char['count_exist']);
                $plan_char = $this->getChar($this->num_char['count_plan']);
                $incurred_char = $this->getChar($this->num_char['count_incurred']);
                $monitoring_staff_char = $this->getChar($this->num_char['count_monitoring_staff']);
                $monitoring_staff_note_char = $this->getChar($this->num_char['count_monitoring_staff_note']);
                $teacher_note_char = $this->getChar($this->num_char['count_teacher_note']);
                $teacher_account_number_char = $this->getChar($this->num_char['count_teacher_account_number']);

                $event->sheet->getDelegate()->mergeCells($total_cost_char.'8:'.$total_cost_char.'9');
                $event->sheet->getDelegate()->mergeCells($recruits_char.'8:'.$recruits_char.'9');
                $event->sheet->getDelegate()->mergeCells($exist_char.'8:'.$exist_char.'9');
                $event->sheet->getDelegate()->mergeCells($plan_char.'8:'.$plan_char.'9');
                $event->sheet->getDelegate()->mergeCells($incurred_char.'8:'.$incurred_char.'9');
                $event->sheet->getDelegate()->mergeCells($monitoring_staff_char.'8:'.$monitoring_staff_char.'9');
                $event->sheet->getDelegate()->mergeCells($monitoring_staff_note_char.'8:'.$monitoring_staff_note_char.'9');
                $event->sheet->getDelegate()->mergeCells($teacher_note_char.'8:'.$teacher_note_char.'9');
                $event->sheet->getDelegate()->mergeCells($teacher_account_number_char.'8:'.$teacher_account_number_char.'9');
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

    public function getChar($number){
        $arr_char = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        if ($number > 26){
            if ($number == 52){
                $char = 'AZ';
            }else{
                $num = floor($number/26);
                $num_1 = $number - ($num * 26);

                $char = $arr_char[($num - 1)] . $arr_char[($num_1 == 0 ? 0 : $num_1 - 1)];
            }
        }else{
            $char = $arr_char[($number - 1)];
        }

        return $char;
    }
}
