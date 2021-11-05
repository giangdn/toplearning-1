<?php
namespace Modules\Quiz\Exports;

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

class HistoryUserSecondExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($search)
    {
        $this->search = $search;
    }

    public function map($row): array
    {
        $this->index++;
        $part = QuizPart::find($row->part_id);
        $result = QuizResult::where('quiz_id', '=', $row->quiz_id)->where('user_id', '=', $row->user_id)->where('type', '=', 2)->first();

        $row->time_start = get_date($part->start_date);
        $row->time_end = get_date($part->end_date);
        $row->grade = $result ? number_format($result->grade, 2) : '';
        $row->result = $result ? ($result->grade >= $row->pass_score ? 'Đạt' : 'Không đạt'): '';

        $row->reexamine = $result ? number_format($result->reexamine, 2) : '';
        $row->result_reexamine = $result ? ($result->reexamine >= $row->pass_score ? 'Đạt' : 'Không đạt'): '';

        return [
            $this->index,
            $row->code,
            $row->name,
            get_date($row->dob),
            $row->identity_card,
            $row->email,
            get_date($row->created_time_user),
            $row->quiz_code,
            $row->quiz_name,
            $row->time_start,
            $row->time_end,
            $row->score,
            $row->result,
            $row->reexamine,
            $row->result_reexamine,
        ];
    }

    public function query(){
        $query = QuizAttempts::query();
        $query->select([
            'a.*',
            'b.code',
            'b.name',
            'b.dob',
            'b.identity_card',
            'b.email',
            'b.created_at as created_time_user',
            'quiz.code as quiz_code',
            'quiz.name as quiz_name',
            'quiz.pass_score'
        ]);
        $query->from('el_quiz_attempts AS a');
        $query->leftJoin('el_quiz_user_secondary AS b', 'b.id', '=', 'a.user_id');
        $query->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'a.quiz_id');
        $query->where('a.type', '=', 2);

        if ($this->search) {
            $query->where(function ($sub_query) {
                $sub_query->orWhere('b.name', 'like', '%'. $this->search .'%');
                $sub_query->orWhere('b.code', 'like', '%'. $this->search .'%');
            });
        }

        $query->orderBy('a.user_id', 'ASC');
        $this->count = $query->count();

        return $query;
    }

    public function headings(): array
    {
        return [
            ['LỊCH SỬ THI TUYỂN THÍ SINH BÊN NGOÀI'],
            [
                'STT',
                'Mã nhân viên',
                'Họ và tên',
                'Ngày sinh',
                'CMND',
                'Email',
                'Ngày tạo',
                'Mã kỳ thi',
                'Tên kỳ thi',
                'Thời gian bắt đầu',
                'Thời gian kết thúc',
                'Điểm',
                'Kết quả',
                'Phúc khảo',
                'Kết quả phúc khảo',
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:O1');
                $event->sheet->getDelegate()->getStyle('A1:O1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor() ->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:O'.(2 + $this->count).'')->applyFromArray([
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
