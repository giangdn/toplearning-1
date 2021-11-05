<?php
namespace Modules\Quiz\Exports;

use App\Models\Categories\Titles;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\WithCharts;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRank;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizUpdateAttempts;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use Carbon\Carbon;

class DashboardExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;
    protected $index = 0;
    protected $count = 0;

    public function __construct($start_date, $end_date, $user_type, $title, $quiz_type, $quiz)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->user_type = $user_type;
        $this->title = $title;
        $this->quiz_type = $quiz_type;
        $this->quiz = $quiz;
    }

    public function map($row): array
    {
        $this->index++;
        $start_date = '';
        $end_date = '';

        $quiz = Quiz::find($row->quiz_id);
        $qdate = QuizPart::where('quiz_id', '=', $row->quiz_id)->first(\DB::raw('MIN(start_date) as start_date'));
        if ($qdate->exists()) {
            $start_date = $qdate->start_date;
        }

        $qdate = QuizPart::where('quiz_id', '=', $row->quiz_id)->first(\DB::raw('MAX(end_date) as end_date'));
        if ($qdate->exists()) {
            $end_date = $qdate->end_date;
        }

        $quiz_register = QuizRegister::whereQuizId($row->quiz_id);
        if ($this->user_type){
            $quiz_register->where('type', '=', $this->user_type);
        }
        $quiz_register = $quiz_register->count();

        $result_1 = QuizResult::where('quiz_id', '=', $row->quiz_id)->where('result', '=', 1);
        if ($this->user_type){
            $result_1->where('type', '=', $this->user_type);
        }
        $result_1 = $result_1->count();

        $result_0 = QuizResult::where('quiz_id', '=', $row->quiz_id)->where('result', '=', 0);
        if ($this->user_type){
            $result_0->where('type', '=', $this->user_type);
        }
        $result_0 = $result_0->count();
        $absent = $quiz_register - ($result_1 + $result_0);

        return [
            $this->index,
            $quiz->name,
            get_date($start_date) . ($end_date ? ' đến '. get_date($end_date) : ''),
            $quiz_register.'',
            $result_1.'',
            $result_0.'',
            $absent.'',
        ];
    }

    public function query(){
        $query = QuizRegister::query()
            ->select([
                'register.quiz_id',
            ])
            ->from('el_quiz_register as register')
            ->leftJoin('el_quiz_part as part', 'part.quiz_id', '=', 'register.quiz_id')
            ->leftJoin('el_quiz as quiz', 'quiz.id', '=', 'register.quiz_id')
            ->leftJoin('el_profile_view AS profile', function ($join) {
                $join->on('profile.user_id', '=', 'register.user_id')
                    ->where('register.type', '=', 1);
            })
            ->leftJoin('el_titles as title', 'title.code', '=', 'profile.title_code')
            ->where('part.start_date', '>=', date_convert($this->start_date, '00:00:00'))
            ->where('part.end_date', '<=', date_convert($this->end_date, '23:59:59'));

        if ($this->user_type){
            $query->where('register.type', '=', $this->user_type);
        }
        if ($this->title){
            $query->where('title.id', '=', $this->title);
        }

        if ($this->quiz_type){
            $query->whereIn('quiz.type_id', $this->quiz_type);
        }

        if ($this->quiz){
            $query->where('quiz.id', $this->quiz);
        }

        $query->groupBy('register.quiz_id');
        $query->orderBy('register.quiz_id', 'ASC');
        $this->count = $query->count();

        return $query;
    }

    public function headings(): array
    {
        $user_type = $this->user_type == 1 ? 'Nội bộ' : ($this->user_type == 2 ? 'Bên ngoài' : '');
        $title = Titles::find($this->title);
        $quiz = Quiz::find($this->quiz);
        $quiz_type = QuizType::find($this->quiz_type);

        $title_arr[] = 'STT';
        $title_arr[] = 'Tên kỳ thi';
        $title_arr[] = 'Thời gian';
        $title_arr[] = 'Ghi danh';
        $title_arr[] = 'Đạt';
        $title_arr[] = 'Không đạt';
        $title_arr[] = 'Vắng';

        return [
            ['Thống kê số lượng thí sinh'],
            ['Thời gian ', $this->start_date . ' đến ' . $this->end_date],
            ['Loại thí sinh ', $user_type],
            ['Chức danh ', @$title->name],
            ['Tên kỳ thi ', @$quiz->name],
            ['Loại kỳ thi ', @$quiz_type->name],
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
                $event->sheet->getDelegate()->mergeCells('A1:G1')->getStyle('A1')->applyFromArray($title);

                $event->sheet->getDelegate()->getStyle('A8:G'.(8 + $this->count))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

            },

        ];
    }
}
