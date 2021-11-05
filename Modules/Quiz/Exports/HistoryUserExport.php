<?php
namespace Modules\Quiz\Exports;

use App\Profile;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizResult;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class HistoryUserExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($unit, $title, $status, $search)
    {
        $this->status = $status;
        $this->unit = $unit;
        $this->title = $title;
        $this->search = $search;
    }

    public function map($row): array
    {
        $this->index++;
        $part = QuizPart::find($row->part_id);
        $result = QuizResult::where('quiz_id', '=', $row->quiz_id)->where('user_id', '=', $row->user_id)->where('type', '=', 1)->first();

        $row->time_start = get_date($part->start_date);
        $row->time_end = get_date($part->end_date);
        $row->score = $result ? (isset($result->reexamine) ? number_format($result->reexamine, 2) : number_format($result->grade, 2)) : '';
        $row->result = $result ? ($result->result == 1 ? 'Đạt' : 'Không đạt'): '';

        switch ($row->profile_status) {
            case 0:
                $row->profile_status = trans('backend.inactivity'); break;
            case 1:
                $row->profile_status = trans('backend.doing'); break;
            case 2:
                $row->profile_status = trans('backend.probationary'); break;
            case 3:
                $row->profile_status = trans('backend.pause'); break;
        }

        return [
            $this->index,
            $row->profile_code,
            Profile::fullname($row->user_id),
            $row->profile_email,
            $row->title_name,
            $row->unit_name,
            $row->profile_status,
            $row->quiz_code,
            $row->quiz_name,
            $row->time_start,
            $row->time_end,
            $row->score,
            $row->result
        ];
    }

    public function query(){
        $query = QuizAttempts::query();
        $query->select([
            'a.*',
            'b.code AS profile_code',
            'b.email AS profile_email',
            'b.status as profile_status',
            'c.name AS title_name',
            'd.name AS unit_name',
            'quiz.code as quiz_code',
            'quiz.name as quiz_name',
        ]);
        $query->from('el_quiz_attempts AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'a.quiz_id');
        $query->where('a.type', '=', 1);
        $query->where('b.user_id', '>', 2);

        if ($this->search) {
            $query->where(function ($sub_query) {
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%'. $this->search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $this->search .'%');
            });
        }

        if (!is_null($this->status)) {
            $query->where('b.status', '=', $this->status);
        }

        if ($this->title) {
            $query->where('c.id', '=', $this->title);
        }

        if ($this->unit) {
            $query->where('d.id', '=',  $this->unit);
        }

        $query->orderBy('a.user_id', 'ASC');
        $this->count = $query->count();

        return $query;
    }

    public function headings(): array
    {
        return [
            ['LỊCH SỬ THI TUYỂN THÍ SINH NÔI BỘ'],
            [
                'STT',
                'Mã nhân viên',
                'Họ và tên',
                'Email',
                'Chức danh',
                'Đơn vị',
                'Trạng thái',
                'Mã kỳ thi',
                'Tên kỳ thi',
                'Thời gian bắt đầu',
                'Thời gian kết thúc',
                'Điểm',
                'Kết quả',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:M1');
                $event->sheet->getDelegate()->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor() ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:M'.(2 + $this->count).'')->applyFromArray([
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
