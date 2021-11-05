<?php
namespace Modules\Report\Export;

use App\Profile;
use App\Models\Categories\TrainingProgram;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Report\Entities\BC23;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class BC23Export implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,  WithEvents, WithStartRow
{
    use Exportable;
    protected $index = 0;
    protected $total_course = 0;

    public function __construct($param)
    {
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;
        $this->training_program_id = $param->training_program_id;
    }

    public function query()
    {
        $query = BC23::sql($this->training_program_id, $this->from_date, $this->to_date)->orderBy('user_id', 'ASC');
        return $query;
    }
    public function map($report): array
    {
        $this->index++;
        $user = $this->getUser($report->user_id);

        $result = [];
        $result[] = $this->index;
        $result[] = $user->code;
        $result[] = $user->lastname . ' '. $user->firstname;
        $result[] = $user->unit_name;
        $result[] = $user->title_name;

        $course = $this->getCountByTraningProgram();
        foreach ($course as $item){
            $score_user = $this->getScore($report->user_id, $item->id, $item->course_type);

            $result[] = $score_user ? $score_user->score : '';
        }

        return $result;

    }
    public function headings(): array
    {
        $title = [];
        $title[] = 'STT';
        $title[] = 'MSNV';
        $title[] = 'Học viên';
        $title[] = 'Đơn vị';
        $title[] = 'Chức danh';

        $course = $this->getCountByTraningProgram();
        $this->total_course = $course->count();

        foreach ($course as $item){
            $title[] = $item->name;
        }
        $training_program = TrainingProgram::find($this->training_program_id);
        $training_program_name = Str::upper($training_program->name);

        return [
            ['THỐNG KÊ KẾT QUẢ KHÓA HỌC THEO '. $training_program_name],
            [$this->from_date ? 'Từ '. $this->from_date. ' đến '. $this->to_date : ''],
            $title
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
                        'name'      => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $char = chr(ord('E') + $this->total_course);

                $event->sheet->getDelegate()->mergeCells('A1:'.$char.'1')->getStyle('A1')->applyFromArray($title);
                $event->sheet->getDelegate()->mergeCells('A2:'.$char.'2')->getStyle('A2')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A3:'.$char.''.(3 + $this->index))
                    ->getAlignment()
                    ->setWrapText(true);

                $event->sheet->getDelegate()->getStyle('A3:'.$char.''.(3 + $this->index))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $event->sheet->getDelegate()->getRowDimension(3)->setRowHeight(40);

                $course = $this->getCountByTraningProgram();
                foreach ($course as $key => $item){
                    if ($key == 0){
                        $chr = 'F';
                    }

                    $event->sheet->getDelegate()->getColumnDimension($chr)->setAutoSize(false);
                    $event->sheet->getDelegate()->getColumnDimension($chr)->setWidth(14);

                    $chr = chr(ord($chr) + 1);
                }

            },

        ];
    }
    public function startRow(): int
    {
        return 2;
    }

    public function getCountByTraningProgram(){
        $course = \DB::query()
            ->select('id', 'course_type', 'name')
            ->from('el_course_view')
            ->where('status', '=', 1)
            ->where('training_program_id', '=', $this->training_program_id);
        if ($this->from_date && $this->to_date){
            $course = $course->where('start_date','>=', date_convert($this->from_date))
                ->where('start_date','<=', date_convert($this->to_date,'23:59:59'));
        };
        $course = $course->get();

        return $course;
    }

    public function getUser($user_id){
        $users = Profile::query()
            ->select([
                'a.user_id',
                'a.code',
                'a.lastname',
                'a.firstname',
                'b.name AS title_name',
                'c.name AS unit_name'
            ])
            ->from('el_profile as a')
            ->leftJoin('el_titles as b', 'b.code', '=', 'a.title_code')
            ->leftJoin('el_unit as c', 'c.code', '=', 'a.unit_code')
            ->where('a.user_id', '=', $user_id)
            ->first();
        return $users;
    }

    public function getScore($user_id, $course_id, $course_type){
        $query = \DB::query()
            ->select(['score'])
            ->from('el_course_register_view')
            ->where('user_id', '=', $user_id)
            ->where('course_id', '=', $course_id)
            ->where('course_type', '=', $course_type)
            ->first();

        return $query;
    }
}
