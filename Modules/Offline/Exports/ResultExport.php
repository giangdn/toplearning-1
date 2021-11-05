<?php
namespace Modules\Offline\Exports;

use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineAttendance;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Rating\Entities\RatingCourse;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ResultExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $total_percent = 0;
    protected $count = 0;

    public function __construct($course_id)
    {
        $this->course_id = $course_id;
    }

    public function map($result): array
    {
        $this->index++;
        $result_off = OfflineResult::where('register_id', '=', $result->id)->first();

        $this->total_percent = OfflineResult::getPercent($result->id);
        $rating = RatingCourse::checkExists($this->course_id, $result->user_id, 2) ? 1 : 0;
        return [
            $this->index,
            $result->profile_code,
            $result->profile_lastname . ' ' .  $result->profile_firstname,
            $this->total_percent . ' %',
            $result_off ? $result_off->score ? $result_off->score : '' : '',
            $rating == 1 ? 'x' : '',
            $result_off ? $result_off->result == 1 ? 'Hoàn thành' : ($result_off->result == 0 ? 'Không hoàn thành' : 'Chưa hoàn thành') : '',
            $result_off ? $result_off->note : '',
        ];
    }

    public function query(){
        $query = OfflineRegister::query();
        $query->select([
            'b.id',
            'c.user_id',
            'c.code as profile_code',
            'c.lastname as profile_lastname',
            'c.firstname as profile_firstname'
        ]);
        $query->from('el_offline_register AS b');
        $query->leftJoin('el_profile AS c', 'c.user_id', '=', 'b.user_id');
        $query->where('b.course_id', '=', $this->course_id);
        $query->orderBy('b.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        $course = OfflineCourse::find($this->course_id);
        return [
            ['Kết quả đào tạo ' . $course->name],
            ['STT', 'Mã nhân viên', 'Họ và tên', 'Tham gia', 'Điểm thi', 'Đánh giá khóa học', 'Kết quả', 'Ghi chú'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->mergeCells('A1:H1');
                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:H'.(2 + $this->count).'')->applyFromArray([
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
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

            },
        ];
    }
}