<?php


namespace Modules\Potential\Exports;

use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\KPI;
use App\Profile;
use App\Certificate;
use Modules\Capabilities\Entities\CapabilitiesGroupPercent;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Potential\Entities\Potential;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PotentialExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{

    use Exportable;

    protected $index = 0;
    protected $count = 0;

    public function __construct($cert, $from_percent, $to_percent, $from_year, $to_year, $start_date, $end_date)
    {
        $this->cert = $cert;
        $this->from_percent = $from_percent;
        $this->to_percent = $to_percent;
        $this->from_year = $from_year;
        $this->to_year = $to_year;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function map($profile): array
    {
        $this->index++;

        $join_company = get_date($profile->join_company, 'Y-m-d');
        $now = date('Y-m-d');

        $count_complete = $this->countCourseCompleteUserTrainingRoadmap($profile->user_id);
        $count_course = $this->countCourseRequired($profile->user_id);
        $check = ($count_complete > 0 && $count_complete == $count_course) ? 1 : 0;

        return [
            $this->index,
            $profile->code,
            $profile->lastname . ' ' . $profile->firstname,
            $profile->title_name,
            $profile->unit_name,
            $profile->ratio .' %',
            $profile->group_percent,
            $profile->certificate_name,
            $profile->expbank,
            $profile->d1,
            $profile->d2,
            $profile->d3,
            $check == 1 ? x : '',
        ];
    }

    public function query()
    {
        $query = Potential::query();
        $query->select([
            'a.ratio',
            'a.group_percent',
            'a.d1',
            'a.d2',
            'a.d3',
            'b.*',
            'c.name AS title_name',
            'd.name AS unit_name',
            'e.certificate_name',
        ]);
        $query->from('el_potential AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'b.unit_code');
        $query->leftJoin('el_cert AS e', 'e.certificate_code', '=', 'b.certificate_code');

        if ($this->cert) {
            $query->whereIn('e.id', explode(',', $this->cert));
        }

        if ($this->to_percent) {
            $query->where(\DB::raw('ISNULL((sum_practical_goal / sum_goal) * 100, 0)'), '<=', (float)$this->to_percent);
        }

        if ($this->from_percent) {
            $query->where(\DB::raw('ISNULL((sum_practical_goal / sum_goal) * 100, 0)'), '>=', (float)$this->from_percent);
        }

        if ($this->from_year) {
            $query->where(\DB::raw('CONVERT(float, expbank)'), '>=', (float)$this->from_year);
        }

        if ($this->to_year) {
            $query->where(\DB::raw('CONVERT(float, expbank)'), '<=', (float)$this->to_year);
        }

        if ($this->start_date) {
            $query->where(\DB::raw('ROUND(CONVERT(float, DATEDIFF(day, join_company, GETDATE()))/365, 2)'), '>=', floatval($this->start_date));
        }

        if ($this->end_date) {
            $query->where(\DB::raw('ROUND(CONVERT(float, DATEDIFF(day, join_company, GETDATE()))/365, 2)'), '<=', floatval($this->end_date));
        }

        $query->orderBy('a.id', 'ASC');

        $this->count = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return [
            ['Danh sách nhân sự tiềm năng'],
            [
                'STT',
                'Mã nhân viên',
                'Tên nhân viên',
                'Chức danh',
                'Đơn vị',
                'Tỷ lệ đánh giá (%)',
                'Nhóm',
                'Trình độ',
                'Thâm niên trong nghề (Năm)',
                'Đợt 1',
                'Đợt 2',
                'Đợt 3',
                'Hoàn thành lớp tiềm năng'
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:N1');
                $event->sheet->getDelegate()->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'size'      =>  13,
                        'bold'      =>  true,
                    ],
                ])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('DDDDDD');

                $event->sheet->getDelegate()->getStyle('A1:N'.(2 + $this->count).'')->applyFromArray([
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
                    ],
                ]);
            },

        ];
    }

    public function countCourseCompleteUserTrainingRoadmap($user_id) {
        $profile = Profile::find($user_id);
        $title = Titles::where('code', '=', $profile->title_code)->first();

        $query = Subject::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_subject AS a');
        $query->whereIn('a.id', function($subquery) use ($title){
            $subquery->select(['subject_id'])
                ->from('el_potential_roadmap')
                ->where('title_id', '=', $title->id);
        });
        $query->where(function ($subquery)  use ($user_id) {
            $subquery->orWhereIn('a.id', function ($subquery2) use ($user_id) {
                $subquery2->select(['el_online_course.subject_id'])
                    ->from('el_online_register')
                    ->join('el_online_course', 'el_online_course.id', '=', 'el_online_register.course_id')
                    ->join('el_online_course_complete', 'el_online_course_complete.course_id', '=', 'el_online_register.course_id')
                    ->where('el_online_course_complete.user_id', '=', $user_id);
            });
        });
        $query->orWhere(function ($subquery)  use ($user_id) {
            $subquery->orWhereIn('a.id', function ($subquery2) use ($user_id) {
                $subquery2->select(['el_offline_course.subject_id'])
                    ->from('el_offline_register')
                    ->join('el_offline_course', 'el_offline_course.id', '=', 'el_offline_register.course_id')
                    ->join('el_offline_course_complete', 'el_offline_course_complete.course_id', '=', 'el_offline_register.course_id')
                    ->where('el_offline_course_complete.user_id', '=', $user_id);
            });
        });

        return $query->count();
    }

    public function countCourseRequired($user_id) {
        $profile = Profile::find($user_id);
        $title = Titles::where('code', '=', $profile->title_code)->first();

        $query = Subject::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_subject AS a');
        $query->whereIn('a.id', function($subquery) use ($title){
            $subquery->select(['subject_id'])
                ->from('el_potential_roadmap')
                ->where('title_id', '=', $title->id);
        });

        return $query->count();
    }
}
